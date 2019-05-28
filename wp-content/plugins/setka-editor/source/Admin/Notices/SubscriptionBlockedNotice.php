<?php
namespace Setka\Editor\Admin\Notices;

use Korobochkin\WPKit\Notices\Notice;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\SetkaEditorAccount;

/**
 * Class SubscriptionBlockedNotice
 */
class SubscriptionBlockedNotice extends Notice
{
    /**
     * @var SetkaEditorAccount
     */
    protected $setkaEditorAccount;

    public function __construct()
    {
        $this->setName(Plugin::NAME . '_subscription_blocked');
    }

    /**
     * @inheritdoc
     */
    public function lateConstruct()
    {
        $this
            ->setView(new NoticeErrorView())
            ->getView()->setCssClasses(array_merge(
                $this->getView()->getCssClasses(),
                array('setka-editor-notice', 'setka-editor-notice-error')
            ));

        $content = __('Setka Editor plugin was deactivated because of the technical error. Please contact Setka Editor team at <a href="mailto:support@setka.io" target="_blank">support@setka.io</a>.', Plugin::NAME);
        $this->setContent('<p>' . $content . '</p>');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isRelevant()
    {
        if (!parent::isRelevant()) {
            return false;
        }

        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!$this->setkaEditorAccount->isLoggedIn()) {
            return false;
        }

        if (!$this->setkaEditorAccount->isSubscriptionStatusRunning()) {
            return true;
        }

        return false;
    }

    /**
     * @return SetkaEditorAccount
     */
    public function getSetkaEditorAccount()
    {
        return $this->setkaEditorAccount;
    }

    /**
     * @param SetkaEditorAccount $setkaEditorAccount
     *
     * @return $this
     */
    public function setSetkaEditorAccount(SetkaEditorAccount $setkaEditorAccount)
    {
        $this->setkaEditorAccount = $setkaEditorAccount;
        return $this;
    }
}
