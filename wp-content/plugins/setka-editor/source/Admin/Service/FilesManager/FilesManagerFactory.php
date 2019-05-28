<?php
namespace Setka\Editor\Admin\Service\FilesManager;

use Setka\Editor\Admin\Options\Files\FileSyncFailureOption;
use Setka\Editor\Admin\Options\Files\FileSyncOption;
use Setka\Editor\Admin\Options\Files\FileSyncStageOption;
use Setka\Editor\Admin\Options\Files\UseLocalFilesOption;
use Setka\Editor\Admin\Service\FilesCleaner\FilesCleaner;
use Setka\Editor\Admin\Service\FilesSync\Synchronizer;
use Setka\Editor\Service\DataFactory;

class FilesManagerFactory
{
    /**
     * Creates FileWatcher instance and setup continueExecution callback.
     *
     * @param $sync bool
     * @param $continueExecution
     * @param $dataFactory DataFactory
     * @param $downloadListOfFiles DownloadListOfFiles
     * @param $filesCleaner FilesCleaner
     * @param $synchronizer Synchronizer
     * @param $downloadAttempts int
     *
     * @return FilesManager
     */
    public static function create(
        $sync,
        $continueExecution,
        DataFactory $dataFactory,
        DownloadListOfFiles $downloadListOfFiles,
        FilesCleaner $filesCleaner,
        Synchronizer $synchronizer,
        $downloadAttempts = 3
    ) {
        /**
         * @var $fileSyncFailureOption FileSyncFailureOption
         * @var $fileSyncOption FileSyncOption
         * @var $fileSyncStageOption FileSyncStageOption
         * @var $useLocalFilesOption UseLocalFilesOption
         */
        $fileSyncFailureOption = $dataFactory->create(FileSyncFailureOption::class);
        $fileSyncOption        = $dataFactory->create(FileSyncOption::class);
        $fileSyncStageOption   = $dataFactory->create(FileSyncStageOption::class);
        $useLocalFilesOption   = $dataFactory->create(UseLocalFilesOption::class);

        return new FilesManager(
            $sync,
            $continueExecution,
            $fileSyncFailureOption,
            $fileSyncOption,
            $fileSyncStageOption,
            $useLocalFilesOption,
            $downloadListOfFiles,
            $filesCleaner,
            $synchronizer,
            $downloadAttempts
        );
    }
}
