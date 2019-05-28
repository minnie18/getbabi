<?php
namespace Setka\Editor\Service\AMP;

use Setka\Editor\Service\AMP\AnimationSanitizer\AttributesFormatConverter;
use Setka\Editor\Service\AMP\AnimationSanitizer\AttributesWithStyleConverter;
use Setka\Editor\Service\AMP\AnimationSanitizer\ConverterInterface;
use Setka\Editor\Service\AMP\AnimationSanitizer\WAAPIConverter;
use Setka\Editor\Service\AMP\SanitizerExceptions\NoModificationsException;

class AnimationSanitizer extends \AMP_Base_Sanitizer
{
    /**
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @var \DOMElement HTML <head>.
     */
    protected $headElement;

    /**
     * @var boolean True if animations exists on the page.
     */
    protected $animationsExists = false;

    /**
     * @var ConverterInterface[]
     */
    protected $converters = array(
        AttributesFormatConverter::class => null,
        AttributesWithStyleConverter::class => null,
        WAAPIConverter::class => null,
    );

    /**
     * @inheritdoc
     */
    public function sanitize()
    {
        try {
            $this->lateConstruct()->convertAnimations();
        } catch (\Exception $exception) {
        }
    }

    /**
     * @return $this
     */
    protected function lateConstruct()
    {
        $this->xpath = $this->createXPath();

        $this->headElement = $this->dom->getElementsByTagName('head')->item(0);

        foreach ($this->converters as $class => $instance) {
            $this->converters[$class] = new $class($this->dom, $this->root_element, $this->xpath);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function convertAnimations()
    {
        foreach ($this->converters as $converter) {
            /**
             * @var $converter ConverterInterface
             */
            try {
                $converter->convert();
                $this->animationsExists = true;
            } catch (NoModificationsException $exception) {
            }
        }
        if ($this->animationsExists) {
            $this->commonAnimation();
        }

        return $this;
    }

    protected function commonAnimation()
    {
        $this->headElement->appendChild($this->createCommonStyles());
        $this->args['setka_amp_service']->setAnimations(true);
    }

    /**
     * @return \DOMElement
     */
    protected function createCommonStyles()
    {
        $style = $this->dom->createElement('style');
        $style->setAttribute('type', 'text/css');
        $style->textContent = '.stk-post.stk-post .stk-anim.stk-anim{transform:var(--stk-transform,none);opacity:var(--stk-opacity,1);}';
        return $style;
    }

    /**
     * @return \DOMXPath
     */
    protected function createXPath()
    {
        return new \DOMXPath($this->dom);
    }
}
