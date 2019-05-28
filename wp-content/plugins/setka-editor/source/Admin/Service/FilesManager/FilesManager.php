<?php
namespace Setka\Editor\Admin\Service\FilesManager;

use Setka\Editor\Admin\Cron\Files\FilesManagerCronEvent;
use Setka\Editor\Admin\Cron\Files\FilesQueueCronEvent;
use Setka\Editor\Admin\Cron\Files\SendFilesStatCronEvent;
use Setka\Editor\Admin\Options\Files\FileSyncFailureOption;
use Setka\Editor\Admin\Options\Files\FileSyncOption;
use Setka\Editor\Admin\Options\Files\FileSyncStageOption;
use Setka\Editor\Admin\Options\Files\UseLocalFilesOption;
use Setka\Editor\Admin\Service\ContinueExecution\OutOfTimeException;
use Setka\Editor\Admin\Service\EditorConfigGenerator\EditorConfigGeneratorFactory;
use Setka\Editor\Admin\Service\FilesCleaner\DeletePostException;
use Setka\Editor\Admin\Service\FilesCleaner\FilesCleaner;
use Setka\Editor\Admin\Service\FilesCreator\FilesCreatorFactory;
use Setka\Editor\Admin\Service\FilesManager\Exceptions\DeletingAttemptsDownloadsMetaException;
use Setka\Editor\Admin\Service\FilesManager\Exceptions\FailureOptionException;
use Setka\Editor\Admin\Service\FilesManager\Exceptions\FlushingCacheException;
use Setka\Editor\Admin\Service\FilesManager\Exceptions\SyncDisabledByUserException;
use Setka\Editor\Admin\Service\FilesSync\Exceptions\LimitDownloadingAttemptsException;
use Setka\Editor\Admin\Service\FilesSync\Synchronizer;
use Setka\Editor\Admin\Service\WPQueryFactory;
use Setka\Editor\PostMetas\AttemptsToDownloadPostMeta;
use Setka\Editor\Service\PostStatuses;
use Setka\Editor\Service\SetkaPostTypes;

class FilesManager
{
    /**
     * @var callable Callback which checked after each iteration in $this->syncFiles().
     */
    protected $continueExecution;

    /**
     * @var boolean True if sync enabled.
     */
    protected $sync;

    /**
     * @var FileSyncFailureOption
     */
    protected $fileSyncFailureOption;

    /**
     * @var FileSyncOption
     */
    protected $fileSyncOption;

    /**
     * @var FileSyncStageOption
     */
    protected $fileSyncStageOption;

    /**
     * @var string[] Sync stages in order to execute.
     */
    protected $stages;

    /**
     * @var string[] Callbacks for each stage.
     */
    protected $stagesCallbackMap = array(
        FileSyncStageOption::DOWNLOAD_FILES_LIST => 'stageDownloadFilesList',
        FileSyncStageOption::CLEANUP => 'stageCleanup',
        FileSyncStageOption::CREATE_ENTRIES => 'stageCreateEntries',
        FileSyncStageOption::DOWNLOAD_FILES => 'stageDownloadFiles',
        FileSyncStageOption::GENERATE_EDITOR_CONFIG => 'stageGenerateEditorConfig',
    );

    /**
     * @var UseLocalFilesOption
     */
    protected $useLocalFilesOption;

    /**
     * @var DownloadListOfFiles
     */
    protected $downloadListOfFiles;

    /**
     * @var FilesCleaner
     */
    protected $filesCleaner;

    /**
     * @var Synchronizer
     */
    protected $synchronizer;

    /**
     * @var integer Number of download attempts.
     */
    protected $downloadAttempts = 3;

    /**
     * FilesManager constructor.
     *
     * @param bool $sync
     * @param callable $continueExecution
     * @param FileSyncFailureOption $fileSyncFailureOption
     * @param FileSyncOption $fileSyncOption
     * @param FileSyncStageOption $fileSyncStageOption
     * @param UseLocalFilesOption $useLocalFilesOption
     * @param DownloadListOfFiles $downloadListOfFiles
     * @param FilesCleaner $filesCleaner
     * @param Synchronizer $synchronizer
     * @param $downloadAttempts int
     */
    public function __construct(
        $sync,
        $continueExecution,
        FileSyncFailureOption $fileSyncFailureOption,
        FileSyncOption $fileSyncOption,
        FileSyncStageOption $fileSyncStageOption,
        UseLocalFilesOption $useLocalFilesOption,
        DownloadListOfFiles $downloadListOfFiles,
        FilesCleaner $filesCleaner,
        Synchronizer $synchronizer,
        $downloadAttempts
    ) {
        $this->sync                  = $sync;
        $this->continueExecution     = $continueExecution;
        $this->fileSyncFailureOption = $fileSyncFailureOption;
        $this->fileSyncOption        = $fileSyncOption;
        $this->fileSyncStageOption   = $fileSyncStageOption;
        $this->useLocalFilesOption   = $useLocalFilesOption;
        $this->downloadListOfFiles   = $downloadListOfFiles;
        $this->filesCleaner          = $filesCleaner;
        $this->synchronizer          = $synchronizer;
        $this->downloadAttempts      = $downloadAttempts;
    }

    public function enableSyncingTasks()
    {
        $task = new FilesManagerCronEvent();
        $task->schedule();

        $task = new FilesQueueCronEvent();
        $task->schedule();

        return $this;
    }

    public function disableSyncingTasks()
    {
        $task = new FilesManagerCronEvent();
        $task->unScheduleAll();

        $task = new FilesQueueCronEvent();
        $task->unScheduleAll();

        $this->disableLocalUsage();

        return $this;
    }

    public function restartSyncing()
    {
        $this->disableLocalUsage();

        $this->fileSyncFailureOption->delete();

        $this->fileSyncStageOption->delete();

        return $this;
    }

    public function disableLocalUsage()
    {
        $this->useLocalFilesOption->delete();
        return $this;
    }

    protected function enableLocalUsage()
    {
        $this->useLocalFilesOption->updateValue(true);
        return $this;
    }

    /**
     * @throws FailureOptionException
     * @throws LimitDownloadingAttemptsException
     * @throws SyncDisabledByUserException
     * @throws OutOfTimeException
     * @throws \Exception
     *
     * @return $this
     */
    public function run()
    {
        if (!$this->fileSyncOption->get() || !$this->sync) {
            throw new SyncDisabledByUserException();
        }

        if ($this->fileSyncFailureOption->get()) {
            throw new FailureOptionException();
        }

        $this->stages  = $this->fileSyncStageOption->getStagesList();
        $stagesCounter = count($this->stages) - 1; // To prevent last stage ('ok') run.

        for ($i = $this->findCurrentStageIndex(); $i < $stagesCounter; $i++) {
            if (!isset($this->stagesCallbackMap[$this->stages[$i]])) {
                throw new \LogicException();
            }
            $this->continueExecution();

            call_user_func(array($this, $this->stagesCallbackMap[$this->stages[$i]]));

            $this->fileSyncStageOption->updateValue($this->stages[$i+1]);
        }

        return $this;
    }

    /**
     * Finds current stage index. Or return first stage index if stage not found.
     * @return int
     */
    protected function findCurrentStageIndex()
    {
        $index = array_search($this->fileSyncStageOption->get(), $this->stages, true);

        if (!is_int($index)) {
            reset($this->stages);
            return key($this->stages);
        }

        return $index;
    }

    /**
     * @throws \Exception
     */
    protected function stageDownloadFilesList()
    {
        $this->downloadListOfFiles->execute();
        $this->resetAllDownloadsCounters();
    }

    /**
     * @throws OutOfTimeException
     * @throws DeletePostException
     */
    protected function stageCleanup()
    {
        $this->filesCleaner->run();
    }

    /**
     * @throws \Exception
     */
    protected function stageCreateEntries()
    {
        $filesCreator = FilesCreatorFactory::createFilesCreator();
        $filesCreator->createPosts();
    }

    /**
     * @throws LimitDownloadingAttemptsException
     * @throws \Exception
     */
    protected function stageDownloadFiles()
    {
        try {
            $this->synchronizer->syncFiles();
        } catch (LimitDownloadingAttemptsException $exception) {
            $this->failureOnSyncing();

            // Send stat
            $sendFilesStatTask = new SendFilesStatCronEvent();
            $sendFilesStatTask->schedule();

            throw $exception;
        }
    }

    /**
     * @throws \Setka\Editor\Admin\Service\EditorConfigGenerator\Exceptions\ConfigFileEntryException
     * @throws \Exception
     */
    protected function stageGenerateEditorConfig()
    {
        $generator = EditorConfigGeneratorFactory::create();
        $generator->generate();

        $sendFilesStatTask = new SendFilesStatCronEvent();
        $sendFilesStatTask->schedule();
    }

    public function failureOnSyncing()
    {
        $this->fileSyncFailureOption->updateValue(true);
        return $this;
    }

    /**
     * Loop over pending files and mark it as drafts.
     *
     * Sync process attempt download this files (drafts) again.
     *
     * @return $this For chain calls.
     */
    public function checkPendingFiles()
    {
        do {
            $query = WPQueryFactory::createWhereFilesIsPending();

            $this->continueExecution();

            if ($query->have_posts()) {
                $query->the_post();
                $post = get_post();

                $attemptsToDownloadMeta = new AttemptsToDownloadPostMeta();
                $attemptsToDownloadMeta->setPostId($post->ID);
                $attempts = (int) $attemptsToDownloadMeta->get();

                if ($attempts < $this->downloadAttempts) {
                    wp_update_post(array(
                        'ID' => $post->ID,
                        'post_status' => PostStatuses::DRAFT,
                    ));
                } else {
                    $this->failureOnSyncing();
                    break;
                }

                $query->rewind_posts();
            }
        } while ($query->have_posts());

        wp_reset_postdata(); // restore globals back

        return $this;
    }

    /**
     * Mark all files in DB as archived.
     *
     * After this operation this files will no longer affects downloading queue.
     *
     * @return mixed Result of SQL request with $wpdb->query().
     *
     * @throws FlushingCacheException If cache flushing was failed.
     */
    public function markAllFilesAsArchived()
    {
        global $wpdb;

        $queryResult = $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->posts}
            SET
            post_status = %s
            WHERE
            post_type = %s",
            PostStatuses::ARCHIVE,
            SetkaPostTypes::FILE_POST_NAME
        ));

        $result = wp_cache_flush();

        // Different flushing mechanisms working different.
        // For example Memcached returns null as successful result.
        if (false === $result) {
            throw new FlushingCacheException();
        }

        return $queryResult;
    }

    /**
     * Completely remove downloads counters from post meta for all posts.
     *
     * And also resetting object cache.
     *
     * @return $this For chain calls.
     *
     * @throws FlushingCacheException If can't reset the object cache.
     * @throws DeletingAttemptsDownloadsMetaException If can't delete post metas from DB.
     */
    public function resetAllDownloadsCounters()
    {
        $result = wp_cache_flush();

        // Different flushing mechanisms working different.
        // For example Memcached returns null as successful result.
        if (false === $result) {
            throw new FlushingCacheException();
        }

        unset($result);
        global $wpdb;

        $attemptsDownloadsMeta = new AttemptsToDownloadPostMeta();

        $result = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
            $attemptsDownloadsMeta->getName()
        ));

        if (!is_numeric($result)) {
            throw new DeletingAttemptsDownloadsMetaException();
        }

        return $this;
    }

    public function getFilesStat()
    {
        global $wpdb;

        $stat = array(
            PostStatuses::ANY     => 0,
            PostStatuses::ARCHIVE => 0,
            PostStatuses::DRAFT   => 0,
            PostStatuses::PUBLISH => 0,
            PostStatuses::TRASH   => 0,
            PostStatuses::FUTURE  => 0,
            PostStatuses::PENDING => 0,
        );

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT
            post_status AS status,
            COUNT(ID) AS counter
            FROM {$wpdb->posts}
            WHERE post_type = %s
            GROUP BY post_status",
            SetkaPostTypes::FILE_POST_NAME
        ));

        if (is_array($results)) {
            foreach ($results as $result) {
                $stat[$result->status] = (int) $result->counter;
            }
        }
        unset($results, $result);

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT COUNT(*) as amount
            FROM {$wpdb->posts}
            WHERE post_type = %s",
            SetkaPostTypes::FILE_POST_NAME
        ));

        if (is_array($results)) {
            $stat[PostStatuses::ANY] = (int) $results[0]->amount;
        }

        return $stat;
    }

    /**
     * @param callable $continueExecution
     *
     * @return $this For chain calls.
     */
    public function setContinueExecution($continueExecution)
    {
        $this->continueExecution = $continueExecution;
        return $this;
    }

    /**
     * @return true If we can continue execution.
     * @throws OutOfTimeException If time of current process is over.
     */
    public function continueExecution()
    {
        return call_user_func($this->continueExecution);
    }
}
