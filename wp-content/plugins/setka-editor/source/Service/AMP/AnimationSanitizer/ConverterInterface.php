<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

use Setka\Editor\Service\AMP\SanitizerExceptions\NoModificationsException;

interface ConverterInterface
{
    /**
     * @param \DOMDocument $dom
     * @param \DOMElement $rootElement
     * @param \DOMXPath $xpath
     */
    public function __construct(\DOMDocument $dom, \DOMElement $rootElement, \DOMXPath $xpath);

    /**
     * @throws NoModificationsException
     * @throws \Exception
     */
    public function convert();
}
