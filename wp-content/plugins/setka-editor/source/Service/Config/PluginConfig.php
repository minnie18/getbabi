<?php
namespace Setka\Editor\Service\Config;

use Setka\Editor\Admin\Service\ContinueExecution\CronLock;

class PluginConfig
{

    public static function getUpgradeUrl()
    {
        return apply_filters('setka_editor_upgrade_url', 'https://editor.setka.io/app/billing_plans');
    }

    /**
     * Check for wp.com env.
     *
     * @return bool True if WordPress.com env, false otherwise.
     */
    public static function isVIP()
    {
        if (defined('WPCOM_IS_VIP_ENV') && true === WPCOM_IS_VIP_ENV) {
            // Running on WordPress.com
            return true;
        }
        return false;
    }

    /**
     * @return bool True if sync files enabled.
     */
    public static function isSyncFiles()
    {
        return defined('SETKA_EDITOR_SYNC_FILES') ? SETKA_EDITOR_SYNC_FILES : true;
    }

    /**
     * @return bool True if cron process.
     */
    public static function isCron()
    {
        return defined('DOING_CRON') && true === DOING_CRON;
    }

    /**
     * @return bool True if WP_DEBUG mode enabled.
     */
    public static function isDebug()
    {
        return defined('WP_DEBUG') && true === WP_DEBUG;
    }

    /**
     * @return bool True if run from WP_CLI.
     */
    public static function isCli()
    {
        return defined('WP_CLI') && true === WP_CLI;
    }

    /**
     * @return bool True if logging should be enabled.
     */
    public static function isLog()
    {
        return !(defined('SETKA_EDITOR_PHP_UNIT') && true === SETKA_EDITOR_PHP_UNIT);
    }

    /**
     * Check for Gutenberg env.
     *
     * @return bool True if Gutenberg activated.
     */
    public static function isGutenberg()
    {
        $plugins = get_option('active_plugins');

        if (is_array($plugins) && in_array('classic-editor/classic-editor.php', $plugins, true)) {
            return false;
        }

        if (function_exists('register_block_type')
            &&
            function_exists('do_blocks')
            &&
            function_exists('parse_blocks')
            &&
            function_exists('wp_set_script_translations')
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return array All post types which can used with Setka Editor.
     */
    public static function getAvailablePostTypes()
    {
        $types = get_post_types();

        $unusedTypes = array(
            'attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'user_request',
            'amp_validated_url',
        );

        foreach ($unusedTypes as $type) {
            unset($types[$type]);
        }

        return $types;
    }

    /**
     * @return callable Could we continue run the code?
     */
    public static function getContinueExecution()
    {
        if (self::isCron()) {
            return array(CronLock::class, 'check');
        }
        return '__return_true';
    }
}
