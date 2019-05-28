<?php
namespace Setka\Editor\Admin\Service;

use Korobochkin\WPKit\Pages\PageInterface;
use Korobochkin\WPKit\ScriptsStyles\AbstractScriptsStyles;
use Korobochkin\WPKit\ScriptsStyles\ScriptsStylesInterface;
use Setka\Editor\Admin\Ajax\DismissNoticesAction;
use Setka\Editor\Admin\Service\Js\EditorAdapterJsSettings;
use Setka\Editor\Plugin;

/**
 * Class ScriptStyles
 */
class AdminScriptStyles extends AbstractScriptsStyles implements ScriptsStylesInterface
{
    /**
     * @var EditorAdapterJsSettings
     */
    protected $editorAdapterJsSettings;

    /**
     * @var \WP_Screen
     */
    protected $screen;

    /**
     * @inheritdoc
     */
    public function register()
    {
        $url     = $this->getBaseUrl();
        $version = Plugin::VERSION;
        $debug   = $this->isDev();

        // All styles in single file by now
        $file = ($debug) ? 'assets/css/admin/main-debug.css' : 'assets/css/admin/main.min.css';
        wp_register_style(
            'setka-editor-wp-admin-main',
            $url . $file,
            array(),
            $version
        );

        $file = 'assets/js/build/editor-adapter.bundle.js';
        wp_register_script(
            'setka-editor-wp-admin-editor-adapter',
            $url . $file,
            array('jquery', 'backbone', 'setka-editor-editor', 'wp-pointer', 'dompurify'), //'dompurify'
            $version,
            true
        );

        $file = ($debug) ? 'assets/js/admin/editor-adapter-initializer/editor-adapter-initializer.js' : 'assets/js/admin/editor-adapter-initializer/editor-adapter-initializer.min.js';
        wp_register_script(
            'setka-editor-wp-admin-editor-adapter-initializer',
            $url . $file,
            array('setka-editor-wp-admin-editor-adapter', 'uri-js'),
            $version,
            true
        );

        $file = ($debug) ? 'assets/js/admin/setting-pages/setting-pages.js' : 'assets/js/admin/setting-pages/setting-pages.min.js';
        wp_register_script(
            'setka-editor-wp-admin-setting-pages',
            $url . $file,
            array('jquery', 'backbone'),
            $version,
            true
        );

        $file  = 'assets/js/admin/setting-pages-initializer/setting-pages-initializer';
        $file .= ($debug) ? '.js' : '.min.js';

        wp_register_script(
            'setka-editor-wp-admin-setting-pages-initializer',
            $url . $file,
            array('setka-editor-wp-admin-setting-pages'),
            $version,
            true
        );

        wp_register_script(
            'setka-editor-wp-admin-common',
            $url . 'assets/js/admin/common/common.js',
            array('jquery'),
            $version,
            true
        );

        wp_register_script(
            'setka-editor-wp-admin-gutenberg-tweaks',
            $url . 'assets/js/build/gutenberg-tweaks.bundle.js',
            array(),
            $version,
            true
        );

        return $this;
    }

    /**
     * Enqueue scripts & styles.
     *
     * @return $this For chain calls.
     */
    public function enqueue()
    {
        $this->enqueueForAllPages();
        if ('edit' === $this->screen->base) {
            $this->enqueueForPostCatalogPages();
        }
        return $this;
    }

    /**
     * Enqueue scripts & styles for all /wp-admin/ pages.
     *
     * @return $this For chain calls.
     */
    public function enqueueForAllPages()
    {
        wp_enqueue_style('setka-editor-wp-admin-main');
        $this->localizeAdminCommon();
        wp_enqueue_script('setka-editor-wp-admin-common');
        return $this;
    }

    /**
     * Enqueue scripts & styles for pages with posts table (list).
     *
     * @return $this For chain calls.
     */
    public function enqueueForPostCatalogPages()
    {
        wp_enqueue_script('setka-editor-wp-admin-gutenberg-tweaks');
        return $this;
    }

    /**
     * Localize Editor Adapter.
     *
     * @return $this For chain calls.
     */
    public function localizeAdminEditorAdapter()
    {
        wp_localize_script(
            'setka-editor-wp-admin-editor-adapter',
            'setkaEditorAdapterL10n',
            array(
                'view' => array(
                    'editor' => array(
                        'tabName' => _x('Setka Editor', 'editor tab name', Plugin::NAME),
                        'switchToDefaultEditorsConfirm' => __('Are you sure that you want to switch to default WordPress editor? You will lose all the formatting and design created in Setka Editor.', Plugin::NAME),
                        'switchToSetkaEditorConfirm' => __('Post will be converted by Setka Editor. Its appearance may change. This action can’t be undone. Continue?', Plugin::NAME)
                    ),
                ),
                'names' => array(
                    'css' => Plugin::NAME,
                    '_'   => Plugin::_NAME_
                ),
                'settings' => $this->getEditorAdapterJsSettings()->getSettings(),
                'pointers' => array(
                    'disabledEditorTabs' => array(
                        'target' => '#wp-content-editor-tools .wp-editor-tabs',
                        'options' => array(
                            'pointerClass' => 'wp-pointer setka-editor-pointer-centered-arrow',
                            'content' => sprintf(
                                '<h3>%s</h3><p>%s</p>',
                                __('Why Text and Visual tabs are blocked?', Plugin::NAME),
                                __('Posts created with Setka Editor may contain complex design elements that are not compatible with other post editors.', Plugin::NAME)
                            ),
                            'position' => array('edge' => 'top', 'align' => 'middle')
                        )
                    )
                )
            )
        );

        return $this;
    }

    /**
     * Localize common scripts for admin pages.
     *
     * @return $this For chain calls.
     */
    public function localizeAdminCommon()
    {
        wp_localize_script(
            'setka-editor-wp-admin-common',
            'setkaEditorCommon',
            array(
                'ajaxName' => Plugin::NAME,
                'notices' => array(
                    'dismissAction' => DismissNoticesAction::class,
                    'dismissIds' => array(
                        'wp-kit-notice-setka-editor_amp_sync_failure',
                    ),
                ),
            )
        );
        return $this;
    }

    /**
     * Enqueue scripts and styles for edit post page.
     *
     * @return $this For chain calls.
     */
    public function enqueueForEditPostPage()
    {
        // Editor
        wp_enqueue_script('setka-editor-editor');
        wp_enqueue_style('setka-editor-editor');
        wp_enqueue_style('setka-editor-theme-resources');

        // Editor Initializer for /wp-admin/ pages
        $this->localizeAdminEditorAdapter();
        wp_enqueue_script('setka-editor-wp-admin-editor-adapter-initializer');

        wp_enqueue_style('wp-pointer');

        return $this;
    }

    /**
     * @return EditorAdapterJsSettings
     */
    public function getEditorAdapterJsSettings()
    {
        return $this->editorAdapterJsSettings;
    }

    /**
     * @param EditorAdapterJsSettings $editorAdapterJsSettings
     *
     * @return $this
     */
    public function setEditorAdapterJsSettings($editorAdapterJsSettings)
    {
        $this->editorAdapterJsSettings = $editorAdapterJsSettings;
        return $this;
    }

    /**
     * @return \WP_Screen
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * @param \WP_Screen $screen
     *
     * @return $this
     */
    public function setScreen(\WP_Screen $screen = null)
    {
        $this->screen = $screen;
        return $this;
    }
}
