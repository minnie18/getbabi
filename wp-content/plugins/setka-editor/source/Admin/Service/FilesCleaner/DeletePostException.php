<?php
namespace Setka\Editor\Admin\Service\FilesCleaner;

class DeletePostException extends \RuntimeException
{
    public function __construct(\WP_Post $post)
    {
        parent::__construct('Cannot delete post with id = ' . (int) $post->ID);
    }
}
