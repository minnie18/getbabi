<?php
namespace Setka\Editor\Admin\Service\FilesSync;

use Psr\Log\LoggerInterface;
use Setka\Editor\Admin\Service\Filesystem\FilesystemFactory;
use Setka\Editor\Service\Config\Files;

class SynchronizerFactory
{
    /**
     * Returns Synchronizer instance.
     *
     * This factory prepare instance to usage.
     * 1. Set filesystem instance.
     * 2. Set downloader instance.
     * 3. Set path where files need to be saved.
     * 4. Set logger.
     * 5. Set ContinueExecution callback for WP CRON.
     *
     * @param $logger LoggerInterface
     * @param $downloadAttempts int
     * @param $continueExecution callable
     *
     * @throws \Exception
     *
     * @return Synchronizer
     */
    public static function create(LoggerInterface $logger, $downloadAttempts, $continueExecution)
    {
        $fs = FilesystemFactory::create();

        $downloader = new WordPressDownloader();

        $path = Files::getPath();

        $sync = new Synchronizer($fs, $downloader, $path, $logger, $downloadAttempts);

        $sync->setContinueExecution($continueExecution);

        return $sync;
    }
}
