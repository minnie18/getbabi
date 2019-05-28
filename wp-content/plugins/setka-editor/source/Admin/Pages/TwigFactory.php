<?php
namespace Setka\Editor\Admin\Pages;

use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

class TwigFactory
{
    /**
     * Creates \Twig\Environment instance.
     *
     * @param string|false $cache Path to folder with cache files or false if cache disabled.
     * @param string $templatesPath Path to folder with Twig templates.
     *
     * @throws \ReflectionException
     *
     * @return \Twig\Environment
     */
    public static function create($cache, $templatesPath)
    {
        if ($cache) {
            $cacheTranslations = $cache . 'translate/';
            $cacheTwig         = $cache . 'twig/';
        } else {
            $cacheTranslations = null;
            $cacheTwig         = false;
        }

        $translator = new Translator('en', null, $cacheTranslations);
        $translator->addLoader('xlf', new XliffFileLoader());

        $reflection = new \ReflectionClass(Form::class);
        $translator->addResource(
            'xlf',
            dirname($reflection->getFileName()) . '/Resources/translations/validators.ru.xlf',
            'en',
            'validators'
        );

        $reflection = new \ReflectionClass(HttpFoundationExtension::class);

        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader(array(
                $templatesPath,
                dirname(dirname($reflection->getFileName())) . '/Resources/views/Form',
            )),
            array(
                'cache' => $cacheTwig,
            )
        );

        $formEngine = new TwigRendererEngine(array('form_div_layout.html.twig'), $twig);
        $twig->addRuntimeLoader(new \Twig\RuntimeLoader\FactoryRuntimeLoader(array(
            FormRenderer::class => function () use ($formEngine) {
                return new FormRenderer($formEngine);
            }
        )));
        $twig->addExtension(new FormExtension());
        $twig->addExtension(new TranslationExtension($translator));

        return $twig;
    }
}
