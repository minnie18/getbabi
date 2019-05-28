<?php
namespace Setka\Editor\Admin\Notices;

use Korobochkin\WPKit\Notices\Notice;
use Setka\Editor\Admin\Options\AMP\AMPSyncFailureNoticeOption;
use Setka\Editor\Admin\Options\AMP\AMPSyncFailureOption;
use Setka\Editor\Admin\Options\AMP\AMPSyncLastFailureNameOption;
use Setka\Editor\Plugin;

class AMPSyncFailureNotice extends Notice
{
    /**
     * @var AMPSyncFailureOption
     */
    protected $ampSyncFailureOption;

    /**
     * @var AMPSyncLastFailureNameOption
     */
    protected $ampSyncLastFailureNameOption;

    /**
     * AMPSyncFailureNotice constructor.
     *
     * @param AMPSyncFailureNoticeOption $ampSyncFailureNoticeOption
     * @param AMPSyncFailureOption $ampSyncFailureOption
     * @param AMPSyncLastFailureNameOption $ampSyncLastFailureNameOption
     */
    public function __construct(
        AMPSyncFailureNoticeOption $ampSyncFailureNoticeOption,
        AMPSyncFailureOption $ampSyncFailureOption,
        AMPSyncLastFailureNameOption $ampSyncLastFailureNameOption
    ) {
        $this
            ->setName(Plugin::NAME . '_amp_sync_failure')
            ->setRelevantStorage($ampSyncFailureNoticeOption)
            ->setDismissible(true)
            ->setAmpSyncFailureOption($ampSyncFailureOption)
            ->setAmpSyncLastFailureNameOption($ampSyncLastFailureNameOption);
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
                array('setka-editor-notice', ' setka-editor-notice-error')
            ));

        $errorCode = $this->getAmpSyncLastFailureNameOption()->get();

        if (is_string($errorCode) && !empty($errorCode)) {
            $content = sprintf(
                __('Setka Editor could not update styles for Google AMP. Error code: <code>%1$s</code>. Please contact Setka Editor team at <a href="mailto:support@setka.io" target="_blank">support@setka.io</a>.', Plugin::NAME),
                esc_html($errorCode)
            );
        } else {
            $content = __('Setka Editor could not update styles for Google AMP. Please contact Setka Editor team at <a href="mailto:support@setka.io" target="_blank">support@setka.io</a>.', Plugin::NAME);
        }

        $content = '<p>' . $content . '</p>';

        $this->setContent($content);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isRelevant()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        if (!$this->getAmpSyncFailureOption()->get()) {
            return false;
        }

        return parent::isRelevant();
    }

    /**
     * @return AMPSyncFailureOption
     */
    public function getAmpSyncFailureOption()
    {
        return $this->ampSyncFailureOption;
    }

    /**
     * @param AMPSyncFailureOption $ampSyncFailureOption
     * @return $this
     */
    public function setAmpSyncFailureOption(AMPSyncFailureOption $ampSyncFailureOption)
    {
        $this->ampSyncFailureOption = $ampSyncFailureOption;
        return $this;
    }

    /**
     * @return AMPSyncLastFailureNameOption
     */
    public function getAmpSyncLastFailureNameOption()
    {
        return $this->ampSyncLastFailureNameOption;
    }

    /**
     * @param AMPSyncLastFailureNameOption $ampSyncLastFailureNameOption
     * @return $this
     */
    public function setAmpSyncLastFailureNameOption(AMPSyncLastFailureNameOption $ampSyncLastFailureNameOption)
    {
        $this->ampSyncLastFailureNameOption = $ampSyncLastFailureNameOption;
        return $this;
    }
}
