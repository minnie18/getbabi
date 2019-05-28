<?php
namespace Setka\Editor\Service\SetkaAccount;

use Setka\Editor\Admin\Service\FilesManager\FilesManager;
use Setka\Editor\Admin\Service\SetkaEditorAPI;
use Setka\Editor\Admin\Service\SetkaEditorAPI\Actions;
use Setka\Editor\Admin\Cron;
use Setka\Editor\Admin\Options;
use Setka\Editor\Service\Config\PluginConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SignIn
{
    /**
     * @var SetkaEditorAPI\API
     */
    protected $api;

    /**
     * @var FilesManager
     */
    protected $filesManager;

    /**
     * @var Cron\AMPStylesCronEvent
     */
    protected $ampStylesCronEvent;

    /**
     * @var Cron\AMPStylesQueueCronEvent
     */
    protected $ampStylesQueueCronEvent;

    /**
     * @var Cron\SyncAccountCronEvent
     */
    protected $syncAccountCronEvent;

    /**
     * @var Cron\UpdateAnonymousAccountCronEvent
     */
    protected $updateAnonymousAccountCronEvent;

    /**
     * @var Cron\UserSignedUpCronEvent
     */
    protected $userSignedUpCronEvent;

    /**
     * @var Options\PlanFeatures\PlanFeaturesOption
     */
    protected $planFeaturesOption;

    /**
     * @var Options\EditorCSSOption
     */
    protected $editorCSSOption;

    /**
     * @var Options\EditorJSOption
     */
    protected $editorJSOption;

    /**
     * @var Options\EditorVersionOption
     */
    protected $editorVersionOption;

    /**
     * @var Options\PublicTokenOption
     */
    protected $publicTokenOption;

    /**
     * @var Options\SetkaPostCreatedOption
     */
    protected $setkaPostCreatedOption;

    /**
     * @var Options\SubscriptionActiveUntilOption
     */
    protected $subscriptionActiveUntilOption;

    /**
     * @var Options\SubscriptionPaymentStatusOption
     */
    protected $subscriptionPaymentStatusOption;

    /**
     * @var Options\SubscriptionStatusOption
     */
    protected $subscriptionStatusOption;

    /**
     * @var Options\ThemePluginsJSOption
     */
    protected $themePluginsJSOption;

    /**
     * @var Options\ThemeResourceCSSOption
     */
    protected $themeResourceCSSOption;

    /**
     * @var Options\ThemeResourceJSOption
     */
    protected $themeResourceJSOption;

    /**
     * @var Options\TokenOption
     */
    protected $tokenOption;

    /**
     * SignIn constructor.
     *
     * @param SetkaEditorAPI\API $api
     * @param FilesManager $filesManager
     * @param Cron\AMPStylesCronEvent $ampStylesCronEvent
     * @param Cron\AMPStylesQueueCronEvent $ampStylesQueueCronEvent
     * @param Cron\SyncAccountCronEvent $syncAccountCronEvent
     * @param Cron\UpdateAnonymousAccountCronEvent $updateAnonymousAccountCronEvent
     * @param Cron\UserSignedUpCronEvent $userSignedUpCronEvent
     * @param Options\PlanFeatures\PlanFeaturesOption $planFeaturesOption
     * @param Options\EditorCSSOption $editorCSSOption
     * @param Options\EditorJSOption $editorJSOption
     * @param Options\EditorVersionOption $editorVersionOption
     * @param Options\PublicTokenOption $publicTokenOption
     * @param Options\SetkaPostCreatedOption $setkaPostCreatedOption
     * @param Options\SubscriptionActiveUntilOption $subscriptionActiveUntilOption
     * @param Options\SubscriptionPaymentStatusOption $subscriptionPaymentStatusOption
     * @param Options\SubscriptionStatusOption $subscriptionStatusOption
     * @param Options\ThemePluginsJSOption $themePluginsJSOption
     * @param Options\ThemeResourceCSSOption $themeResourceCSSOption
     * @param Options\ThemeResourceJSOption $themeResourceJSOption
     * @param Options\TokenOption $tokenOption
     */
    public function __construct(
        SetkaEditorAPI\API $api,
        FilesManager $filesManager,
        Cron\AMPStylesCronEvent $ampStylesCronEvent,
        Cron\AMPStylesQueueCronEvent $ampStylesQueueCronEvent,
        Cron\SyncAccountCronEvent $syncAccountCronEvent,
        Cron\UpdateAnonymousAccountCronEvent $updateAnonymousAccountCronEvent,
        Cron\UserSignedUpCronEvent $userSignedUpCronEvent,
        Options\PlanFeatures\PlanFeaturesOption $planFeaturesOption,
        Options\EditorCSSOption $editorCSSOption,
        Options\EditorJSOption $editorJSOption,
        Options\EditorVersionOption $editorVersionOption,
        Options\PublicTokenOption $publicTokenOption,
        Options\SetkaPostCreatedOption $setkaPostCreatedOption,
        Options\SubscriptionActiveUntilOption $subscriptionActiveUntilOption,
        Options\SubscriptionPaymentStatusOption $subscriptionPaymentStatusOption,
        Options\SubscriptionStatusOption $subscriptionStatusOption,
        Options\ThemePluginsJSOption $themePluginsJSOption,
        Options\ThemeResourceCSSOption $themeResourceCSSOption,
        Options\ThemeResourceJSOption $themeResourceJSOption,
        Options\TokenOption $tokenOption
    ) {
        $this->api          = $api;
        $this->filesManager = $filesManager;

        $this->ampStylesCronEvent              = $ampStylesCronEvent;
        $this->ampStylesQueueCronEvent         = $ampStylesQueueCronEvent;
        $this->syncAccountCronEvent            = $syncAccountCronEvent;
        $this->updateAnonymousAccountCronEvent = $updateAnonymousAccountCronEvent;
        $this->userSignedUpCronEvent           = $userSignedUpCronEvent;

        $this->planFeaturesOption              = $planFeaturesOption;
        $this->editorCSSOption                 = $editorCSSOption;
        $this->editorJSOption                  = $editorJSOption;
        $this->editorVersionOption             = $editorVersionOption;
        $this->publicTokenOption               = $publicTokenOption;
        $this->setkaPostCreatedOption          = $setkaPostCreatedOption;
        $this->subscriptionActiveUntilOption   = $subscriptionActiveUntilOption;
        $this->subscriptionPaymentStatusOption = $subscriptionPaymentStatusOption;
        $this->subscriptionStatusOption        = $subscriptionStatusOption;
        $this->themePluginsJSOption            = $themePluginsJSOption;
        $this->themeResourceCSSOption          = $themeResourceCSSOption;
        $this->themeResourceJSOption           = $themeResourceJSOption;
        $this->tokenOption                     = $tokenOption;
    }

    /**
     * Auth from settings pages.
     *
     * By default token updated in DB (but settings pages save token manually).
     *
     * @param $token string New token.
     * @param $updateToken bool Should this function save token or not.
     *
     * @return SetkaEditorAPI\Prototypes\ActionInterface[]
     */
    public function signInByToken($token, $updateToken = true)
    {
        $actions = $this->sendAuthRequests($token);

        if (!$this->isActionsValid($actions)) {
            return $actions;
        }

        if ($updateToken) {
            $this->setupToken($token);
        }

        $this->setupNewAccount(
            $actions[Actions\GetCurrentThemeAction::class],
            $actions[Actions\GetCompanyStatusAction::class]
        );

        return $actions;
    }

    /**
     * Send auth requests and return actions with validated responses.
     *
     * @param $token string Company token (license key).
     *
     * @return SetkaEditorAPI\Prototypes\ActionInterface[] Executed actions
     */
    public function sendAuthRequests($token)
    {
        $this->api->setAuthCredits(new SetkaEditorAPI\AuthCredits($token));

        $currentTheme = new Actions\GetCurrentThemeAction();
        $this->api->request($currentTheme);

        $companyStatus = new Actions\GetCompanyStatusAction();
        $this->api->request($companyStatus);

        return array(
            Actions\GetCurrentThemeAction::class  => $currentTheme,
            Actions\GetCompanyStatusAction::class => $companyStatus,
        );
    }

    /**
     * Check that all actions are valid.
     *
     * @param SetkaEditorAPI\Prototypes\ActionInterface[] $actions
     *
     * @return bool True if all actions without errors.
     */
    public function isActionsValid(array $actions)
    {
        /**
         * @var $action SetkaEditorAPI\Prototypes\ActionInterface
         */
        foreach ($actions as $action) {
            if (!$this->isActionValid($action)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param SetkaEditorAPI\Prototypes\ActionInterface $action
     * @return bool
     */
    public function isActionValid(SetkaEditorAPI\Prototypes\ActionInterface $action)
    {
        if (count($action->getErrors()) === 0) {
            return true;
        }
        return false;
    }

    /**
     * Save token.
     * @param $token string Token.
     * @return $this
     */
    protected function setupToken($token)
    {
        $this->tokenOption->updateValue($token);
        return $this;
    }

    /**
     * @param Actions\GetCurrentThemeAction $currentTheme
     * @param Actions\GetCompanyStatusAction $companyStatus
     * @return $this For chain calls.
     */
    public function setupNewAccount(
        Actions\GetCurrentThemeAction $currentTheme,
        Actions\GetCompanyStatusAction $companyStatus
    ) {
        $this
            ->updateSubscriptionDetails($companyStatus)
            ->updateThemeDetails($currentTheme)
            ->updateEditorDetails($currentTheme)
            ->updateAMPDetails($currentTheme)
            ->resetSetkaPostCreatedFlag()
            ->setupUserSignedUpEvent()
            ->removeAnonymousSync()
            ->setupFileSyncing();

        return $this;
    }

    /**
     * @param Actions\GetCompanyStatusAction $action
     * @return $this
     */
    protected function updateSubscriptionDetails(Actions\GetCompanyStatusAction $action)
    {
        $content = $action->getResponse()->content;

        $this->subscriptionPaymentStatusOption->updateValue($content->get('payment_status'));

        $this->subscriptionStatusOption->updateValue($content->get('status'));

        $this->syncAccountCronEvent->unScheduleAll();

        if ($action->getResponse()->isOk()) {
            $this->subscriptionActiveUntilOption->updateValue($content->get('active_until'));

            $datetime = \DateTime::createFromFormat(
                \DateTime::ISO8601,
                $content->get('active_until')
            );
            if ($datetime) {
                $this->syncAccountCronEvent->setTimestamp($datetime->getTimestamp())->schedule();
            }
        } else {
            $this->subscriptionActiveUntilOption->delete();
        }

        $this->planFeaturesOption->updateValue($content->get('features'));

        return $this;
    }

    /**
     * Reset flag which shows that first Setka Post created.
     * @return $this
     */
    protected function resetSetkaPostCreatedFlag()
    {
        $this->setkaPostCreatedOption->delete();
        return $this;
    }

    /**
     * @param Actions\GetCurrentThemeAction $action
     * @return $this
     */
    protected function updateThemeDetails(Actions\GetCurrentThemeAction $action)
    {
        return $this->updateThemeDetailsCommon($action);
    }

    /**
     * @param Actions\GetCurrentThemeAnonymouslyAction $action
     * @return $this
     */
    protected function updateThemeDetailsAnonymous(Actions\GetCurrentThemeAnonymouslyAction $action)
    {
        return $this->updateThemeDetailsCommon($action);
    }

    /**
     * @param SetkaEditorAPI\Prototypes\ActionInterface $action
     * @return $this
     */
    protected function updateThemeDetailsCommon(SetkaEditorAPI\Prototypes\ActionInterface $action)
    {
        $content = $action->getResponse()->content;

        foreach ($content->get('theme_files') as $file) {
            switch ($file['filetype']) {
                case 'css':
                    $this->themeResourceCSSOption->updateValue($file['url']);
                    break;

                case 'json':
                    $this->themeResourceJSOption->updateValue($file['url']);
                    break;
            }
        }

        if ($content->has('plugins')) {
            $plugins = $content->get('plugins');
            $this->themePluginsJSOption->updateValue($plugins[0]['url']);
        } else {
            $this->themePluginsJSOption->delete();
        }

        return $this;
    }

    /**
     * @param Actions\GetCurrentThemeAction $action
     * @param Actions\GetCompanyStatusAction $companyStatus
     * @return $this
     */
    protected function updateEditorDetails(Actions\GetCurrentThemeAction $action)
    {
        $content = $action->getResponse()->content;

        $this->publicTokenOption->updateValue($content->get('public_token'));

        return $this->updateEditorDetailsCommon($action);
    }

    /**
     * @param Actions\GetCurrentThemeAnonymouslyAction $action
     * @return $this
     */
    protected function updateEditorDetailsAnonymous(Actions\GetCurrentThemeAnonymouslyAction $action)
    {
        return $this->updateEditorDetailsCommon($action);
    }

    /**
     * @param SetkaEditorAPI\Prototypes\ActionInterface $action
     * @return $this
     */
    protected function updateEditorDetailsCommon(SetkaEditorAPI\Prototypes\ActionInterface $action)
    {
        $content = $action->getResponse()->content;

        if ($action->getResponse()->isOk()) {
            foreach ($content->get('content_editor_files') as $file) {
                switch ($file['filetype']) {
                    case 'css':
                        $this->editorCSSOption->updateValue($file['url']);
                        break;

                    case 'js':
                        $this->editorJSOption->updateValue($file['url']);
                        break;
                }
            }
            $this->editorVersionOption->updateValue($content->get('content_editor_version'));
        } elseif ($action->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
            $this->editorJSOption->delete();
            $this->editorCSSOption->delete();
            $this->editorVersionOption->delete();
        }

        return $this;
    }

    /**
     * @param Actions\GetCurrentThemeAction $action
     * @return $this
     */
    protected function updateAMPDetails(Actions\GetCurrentThemeAction $action)
    {
        return $this->updateAMPDetailsCommon($action);
    }

    /**
     * @param Actions\GetCurrentThemeAnonymouslyAction $action
     * @return SignIn
     */
    protected function updateAMPDetailsAnonymous(Actions\GetCurrentThemeAnonymouslyAction $action)
    {
        return $this->updateAMPDetailsCommon($action);
    }

    /**
     * @param SetkaEditorAPI\Prototypes\ActionInterface $action
     * @return $this
     */
    protected function updateAMPDetailsCommon(SetkaEditorAPI\Prototypes\ActionInterface $action)
    {
        $content = $action->getResponse()->content;

        if ($content->has('amp_styles')) {
            try {
                $this->ampStylesCronEvent
                    ->getAmpStylesManager()
                    ->addNewConfig($content->get('amp_styles'));

                $this->ampStylesCronEvent->restart();
                $this->ampStylesQueueCronEvent->restart();
            } catch (\Exception $exception) {
                // Do nothing.
            }
        } else {
            $this->ampStylesCronEvent
                ->getAmpStylesManager()
                ->resetSync();

            $this->ampStylesCronEvent->unscheduleAll();
            $this->ampStylesQueueCronEvent->unscheduleAll();
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setupUserSignedUpEvent()
    {
        $this->userSignedUpCronEvent->unScheduleAll()->schedule();
        return $this;
    }

    /**
     * @return $this
     */
    protected function removeAnonymousSync()
    {
        $this->updateAnonymousAccountCronEvent->unScheduleAll();
        return $this;
    }

    /**
     * @return $this
     */
    protected function reScheduleAnonymousSync()
    {
        $this->updateAnonymousAccountCronEvent->unScheduleAll()->schedule();
        return $this;
    }

    /**
     * @return $this
     */
    protected function setupFileSyncing()
    {
        if (!PluginConfig::isVIP()) {
            $this->filesManager
                ->restartSyncing()
                ->enableSyncingTasks();
        } else {
            $this->filesManager
                ->disableSyncingTasks();
        }
        return $this;
    }

    /**
     * @return Actions\GetCurrentThemeAnonymouslyAction
     */
    public function signInAnonymous()
    {
        $action = $this->sendAuthRequestsAnonymous();

        if (count($action->getErrors()) > 0) {
            return $action;
        }

        $this->setupAnonymousAccount($action);

        return $action;
    }

    /**
     * @param Actions\GetCurrentThemeAnonymouslyAction $currentTheme
     */
    public function setupAnonymousAccount(Actions\GetCurrentThemeAnonymouslyAction $currentTheme)
    {
        $this
            ->updateThemeDetailsAnonymous($currentTheme)
            ->updateEditorDetailsAnonymous($currentTheme)
            ->updateAMPDetailsAnonymous($currentTheme)
            ->reScheduleAnonymousSync();

        $this->subscriptionStatusOption->updateValue('running');

        return $this;
    }

    /**
     * @return Actions\GetCurrentThemeAnonymouslyAction
     */
    public function sendAuthRequestsAnonymous()
    {
        $action = new Actions\GetCurrentThemeAnonymouslyAction();
        $this->api->request($action);
        return $action;
    }

    /**
     * Merge errors from all $actions.
     *
     * @param SetkaEditorAPI\Prototypes\ActionInterface[] $actions Actions with errors.
     * @param ConstraintViolationListInterface $violations Resulted errors list.
     *
     * @return $this For chain calls.
     */
    public function mergeActionErrors(array $actions, ConstraintViolationListInterface $violations)
    {
        $bodyError = false;

        foreach ($actions as $action) {
            foreach ($action->getErrors() as $violation) {
                if (get_class($violation) === ConstraintViolation::class) {
                    $bodyError = true;
                    continue;
                }
                /**
                 * @var $violation ConstraintViolationInterface
                 */
                $violations->set($violation->getCode(), $violation);
            }
        }

        if ($bodyError) {
            $violations->set(
                SetkaEditorAPI\Errors\ResponseBodyInvalidError::class,
                new SetkaEditorAPI\Errors\ResponseBodyInvalidError()
            );
        }

        return $this;
    }
}
