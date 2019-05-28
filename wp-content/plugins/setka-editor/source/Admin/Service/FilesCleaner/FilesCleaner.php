<?php
namespace Setka\Editor\Admin\Service\FilesCleaner;

use Setka\Editor\Admin\Service\ContinueExecution\OutOfTimeException;
use Setka\Editor\Admin\Service\WPQueryFactory;

class FilesCleaner
{
    /**
     * @var callable
     */
    protected $continueExecution;

    /**
     * @param callable $continueExecution
     */
    public function __construct($continueExecution)
    {
        $this->continueExecution = $continueExecution;
    }

    /**
     * @return $this
     * @throws OutOfTimeException
     * @throws DeletePostException
     */
    public function run()
    {
        do {
            $query = WPQueryFactory::createWhereFilesIsPending();

            $this->continueExecution();

            if (!$query->have_posts()) {
                return $this;
            }

            $this->deletePost($query->next_post());
            $query->rewind_posts();
        } while ($query->have_posts());

        return $this;
    }

    /**
     * @param \WP_Post $post
     * @throws DeletePostException
     * @return $this
     */
    protected function deletePost(\WP_Post $post)
    {
        $result = wp_delete_post($post->ID, true);

        if (is_a($result, \WP_Post::class)) {
            return $this;
        }

        throw new DeletePostException($post);
    }

    /**
     * @return $this
     * @throws OutOfTimeException If time of current process is over.
     */
    public function continueExecution()
    {
        call_user_func($this->continueExecution);
        return $this;
    }
}
