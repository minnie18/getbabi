<?php
namespace Setka\Editor\Admin\Pages;

use Setka\Editor\Admin\Options\EditorAccessPostTypesOption;
use Setka\Editor\Admin\Service\AdminScriptStyles;
use Setka\Editor\Admin\User\Capabilities\UseEditorCapability;
use Setka\Editor\Service\ScriptStyles;
use Setka\Editor\Service\SetkaAccount\SetkaEditorAccount;

class EditPost
{
    /**
     * @var boolean
     */
    protected $gutenbergSupport;

    /**
     * @var ScriptStyles
     */
    protected $scriptStyles;

    /**
     * @var AdminScriptStyles
     */
    protected $adminScriptStyles;

    /**
     * @var SetkaEditorAccount
     */
    protected $setkaEditorAccount;

    /**
     * @var EditorAccessPostTypesOption
     */
    protected $editorAccessPostTypesOption;

    /**
     * @var \WP_Post
     */
    protected $post;

    /**
     * EditPost constructor.
     *
     * @param bool $gutenbergSupport
     * @param ScriptStyles $scriptStyles
     * @param AdminScriptStyles $adminScriptStyles
     * @param SetkaEditorAccount $setkaEditorAccount
     * @param EditorAccessPostTypesOption $editorAccessPostTypesOption
     */
    public function __construct(
        $gutenbergSupport,
        ScriptStyles $scriptStyles,
        AdminScriptStyles $adminScriptStyles,
        SetkaEditorAccount $setkaEditorAccount,
        EditorAccessPostTypesOption $editorAccessPostTypesOption
    ) {
        $this->gutenbergSupport            = $gutenbergSupport;
        $this->scriptStyles                = $scriptStyles;
        $this->adminScriptStyles           = $adminScriptStyles;
        $this->setkaEditorAccount          = $setkaEditorAccount;
        $this->editorAccessPostTypesOption = $editorAccessPostTypesOption;
    }

    /**
     * @throws \RuntimeException If current post not found.
     * @return $this
     */
    public function enqueueScripts()
    {
        $this->detectCurrentPost();

        if (!$this->isSetkaEditorSupported()) {
            return $this;
        }

        if ($this->isGutenberg()) {
            $this->scriptStyles->localizeGutenbergBlocks()->enqueueForGutenberg();
        } else {
            $this->adminScriptStyles->enqueueForEditPostPage();
        }

        return $this;
    }

    /**
     * Should Setka Editor enqueued or not.
     *
     * @return bool Enable editor or not.
     */
    public function isSetkaEditorSupported()
    {
        if (!$this->checkUserPermission()) {
            return false;
        }

        if (!$this->setkaEditorAccount->isEditorResourcesAvailable()) {
            return false;
        }

        if (!$this->checkPostType($this->post->post_type)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function checkUserPermission()
    {
        return (bool) current_user_can(UseEditorCapability::NAME);
    }

    /**
     * @param $postType string Post type to check.
     * @return bool True if enabled.
     */
    public function checkPostType($postType)
    {
        if (!is_string($postType) || empty($postType)) {
            return false;
        }

        return in_array(
            $postType,
            $this->editorAccessPostTypesOption->get(),
            true
        );
    }

    /**
     * @throws \RuntimeException
     * @return $this
     */
    public function detectCurrentPost()
    {
        $this->post = get_post();

        if (is_a($this->post, \WP_Post::class)) {
            return $this;
        }

        throw new \RuntimeException();
    }

    /**
     * @return bool
     */
    public function isGutenberg()
    {
        if (!$this->gutenbergSupport) {
            return false;
        }

        if (!function_exists('use_block_editor_for_post')) {
            return false;
        }

        return (bool) use_block_editor_for_post($this->post);
    }
}
