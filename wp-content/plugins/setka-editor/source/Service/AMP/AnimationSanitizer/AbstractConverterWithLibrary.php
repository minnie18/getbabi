<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

abstract class AbstractConverterWithLibrary extends AbstractConverter
{
    /**
     * @var AnimationsLibrary
     */
    protected $animations;

    /**
     * @inheritdoc
     */
    public function __construct(\DOMDocument $dom, \DOMElement $rootElement, \DOMXPath $xpath)
    {
        parent::__construct($dom, $rootElement, $xpath);
        $this->animations = new AnimationsLibrary();
    }

    /**
     * @inheritdoc
     */
    protected function stepInLoop($index)
    {
        $this->convertAnimation($index)->saveAnimationName()->cleanup();
    }

    /**
     * @throws \DomainException
     * @return $this
     */
    protected function saveAnimationName()
    {
        $this->animations->addName($this->getNodeAnimationName());
        return $this;
    }
}
