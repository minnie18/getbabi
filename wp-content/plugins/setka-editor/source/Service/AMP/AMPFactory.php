<?php
namespace Setka\Editor\Service\AMP;

use Setka\Editor\Admin\Options\AMP\AMPCssOption;
use Setka\Editor\Admin\Options\AMP\AMPFontsOption;
use Setka\Editor\Admin\Options\AMP\AMPStylesOption;
use Setka\Editor\Admin\Options\AMP\UseAMPStylesOption;
use Setka\Editor\PostMetas\PostLayoutPostMeta;
use Setka\Editor\PostMetas\PostThemePostMeta;
use Setka\Editor\PostMetas\UseEditorPostMeta;
use Setka\Editor\Service\Config\AMPConfig;
use Setka\Editor\Service\DataFactory;

class AMPFactory
{
    /**
     * @param bool $ampSupport
     * @param string $mode
     * @param AMPCssOption $ampCssOption
     * @param AMPFontsOption $ampFontsOption
     * @param AMPStylesOption $ampStylesOption
     * @param UseAMPStylesOption $useAMPStylesOption
     * @param DataFactory $dataFactory
     * @return AMP
     */
    public static function create(
        $ampSupport,
        $mode,
        AMPCssOption $ampCssOption,
        AMPFontsOption $ampFontsOption,
        AMPStylesOption $ampStylesOption,
        UseAMPStylesOption $useAMPStylesOption,
        DataFactory $dataFactory
    ) {
        /**
         * @var $useEditorPostMeta UseEditorPostMeta
         * @var $postThemePostMeta PostThemePostMeta
         * @var $postLayoutPostMeta PostLayoutPostMeta
         */
        $useEditorPostMeta  = $dataFactory->create(UseEditorPostMeta::class);
        $postThemePostMeta  = $dataFactory->create(PostThemePostMeta::class);
        $postLayoutPostMeta = $dataFactory->create(PostLayoutPostMeta::class);

        if ($ampSupport && !$mode) {
            $mode = AMPConfig::getMode();
        }

        return new AMP(
            $ampSupport,
            $mode,
            $ampCssOption,
            $ampFontsOption,
            $ampStylesOption,
            $useAMPStylesOption,
            $useEditorPostMeta,
            $postThemePostMeta,
            $postLayoutPostMeta
        );
    }
}
