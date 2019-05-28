<?php
namespace Setka\Editor\Admin\Service\FilesCreator;

use Setka\Editor\Admin\Options\Files\FilesOption;
use Setka\Editor\Service\Config\PluginConfig;

class FilesCreatorFactory
{

    public static function createFilesCreator()
    {
        $files        = new FilesOption();
        $filesCreator = new FilesCreator($files);

        $filesCreator->setContinueExecution(PluginConfig::getContinueExecution());

        return $filesCreator;
    }
}
