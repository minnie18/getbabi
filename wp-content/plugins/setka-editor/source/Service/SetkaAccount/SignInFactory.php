<?php
namespace Setka\Editor\Service\SetkaAccount;

use Setka\Editor\Admin\Service\FilesManager\FilesManager;
use Setka\Editor\Admin\Service\SetkaEditorAPI;
use Setka\Editor\Admin\Cron;
use Setka\Editor\Admin\Options;

class SignInFactory
{
    /**
     * @param SetkaEditorAPI\API $api
     * @param FilesManager $filesManager
     * @param Cron\AMPStylesCronEvent $ampStylesCronEvent
     * @param Cron\AMPStylesQueueCronEvent $ampStylesQueueCronEvent
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
     *
     * @return SignIn
     */
    public static function create(
        SetkaEditorAPI\API $api,
        FilesManager $filesManager,
        Cron\AMPStylesCronEvent $ampStylesCronEvent,
        Cron\AMPStylesQueueCronEvent $ampStylesQueueCronEvent,
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
        $syncAccountCronEvent            = new Cron\SyncAccountCronEvent();
        $updateAnonymousAccountCronEvent = new Cron\UpdateAnonymousAccountCronEvent();
        $userSignedUpCronEvent           = new Cron\UserSignedUpCronEvent();

        return new SignIn(
            $api,
            $filesManager,
            $ampStylesCronEvent,
            $ampStylesQueueCronEvent,
            $syncAccountCronEvent,
            $updateAnonymousAccountCronEvent,
            $userSignedUpCronEvent,
            $planFeaturesOption,
            $editorCSSOption,
            $editorJSOption,
            $editorVersionOption,
            $publicTokenOption,
            $setkaPostCreatedOption,
            $subscriptionActiveUntilOption,
            $subscriptionPaymentStatusOption,
            $subscriptionStatusOption,
            $themePluginsJSOption,
            $themeResourceCSSOption,
            $themeResourceJSOption,
            $tokenOption
        );
    }
}
