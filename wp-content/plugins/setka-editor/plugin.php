<?php
use Setka\Editor\Plugin;
use Setka\Editor\Service\Compatibility;
use Setka\Editor\Admin\Service\PHPVersionNotice;
use Setka\Editor\Admin\Service\WPVersionNotice;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/*
Plugin Name: Setka Editor
Plugin URI: https://editor.setka.io/
Description: A WordPress plugin for beautiful content. The editor you've been waiting for to design your posts.
Author: Native Grid LLC
Author URI: https://editor.setka.io/
Version: 1.19.1
Text Domain: setka-editor
Domain Path: /languages/
License: GPLv2 or later
*/

if (!class_exists('Setka\Editor\Plugin')) {
    // If class not exists this means what a wordpress.org version running
    // and we need require our own autoloader.
    // If you using WordPress installation with composer just require
    // your own autoload.php as usual. In this case plugin don't require any
    // additional autoloaders.
    require_once __DIR__ . '/vendor/autoload.php';
}

function setkaEditorRunner()
{
    $compatibility = true;

    // Check for minimum PHP version
    if (!Compatibility::checkPHP(50509)) {
        $PHPVersionNotice = new PHPVersionNotice();
        $PHPVersionNotice
            ->setBaseUrl(plugin_dir_url(__FILE__))
            ->setPluginVersion('1.19.1')
            ->setPhpVersionMin('5.5.9')
            ->run();
        $compatibility = false;
    }

    // Check for minimum WordPress version
    if (!Compatibility::checkWordPress('4.1')) {
        $WPVersionNotice = new WPVersionNotice();
        $WPVersionNotice
            ->setBaseUrl(plugin_dir_url(__FILE__))
            ->setPluginVersion('1.19.1')
            ->setWpVersionMin('4.1')
            ->run();
        $compatibility = false;
    }

    if ($compatibility) {
        $GLOBALS['WPSetkaEditorPlugin'] = new Plugin(__FILE__);

        global $container;
        if (isset($container) && is_a($container, 'Symfony\Component\DependencyInjection\ContainerBuilder')) {
            $GLOBALS['WPSetkaEditorPlugin']->setContainer($container);
        } else {
            $GLOBALS['WPSetkaEditorPlugin']->setContainer(new ContainerBuilder());
        }
        $GLOBALS['WPSetkaEditorPlugin']->run();
    }
}
setkaEditorRunner();
