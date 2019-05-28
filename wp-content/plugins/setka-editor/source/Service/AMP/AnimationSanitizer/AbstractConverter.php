<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

use Setka\Editor\Service\AMP\SanitizerExceptions\NoModificationsException;

abstract class AbstractConverter implements ConverterInterface
{
    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var \DOMElement
     */
    protected $rootElement;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @var \DOMElement Current animated element (tag with data-anim=true).
     */
    protected $node;

    /**
     * @var array Current animation config.
     */
    protected $config;

    /**
     * @var array HTML attributes should be removed.
     */
    protected $attributesToRemove = array(
        'data-anim',
        'data-anim-delay',
        'data-anim-direction',
        'data-anim-duration',
        'data-anim-loop',
        'data-anim-opacity',
        'data-anim-rotation',
        'data-anim-shift',
        'data-anim-trigger',
        'data-anim-zoom',
        'data-anim-played',
    );

    /**
     * AbstractConverter constructor.
     *
     * @param \DOMDocument $dom
     * @param \DOMElement $rootElement
     * @param \DOMXPath $xpath
     */
    public function __construct(\DOMDocument $dom, \DOMElement $rootElement, \DOMXPath $xpath)
    {
        $this->dom         = $dom;
        $this->rootElement = $rootElement;
        $this->xpath       = $xpath;
    }

    /**
     * @inheritdoc
     */
    public function convert()
    {
        $nodes = $this->createNodesList();

        if (count($nodes) === 0) {
            throw new NoModificationsException();
        }

        foreach ($nodes as $index => $this->node) {
            $this->stepInLoop($index);
        }

        $this->cleanupCommon();
    }

    /**
     * @return \DOMNodeList
     */
    protected function createNodeListWithAnimations()
    {
        return $this->xpath->query('//*[@data-anim="true"]');
    }

    /**
     * Return list contains \DOMElement-s with data-anim=true.
     * @return \DOMElement[]
     */
    abstract protected function createNodesList();

    /**
     * @param $index
     * @throws \Exception
     */
    protected function stepInLoop($index)
    {
        $this->convertAnimation($index)->cleanup();
    }

    /**
     * @param $index
     * @throws \Exception
     * @return $this
     */
    protected function convertAnimation($index)
    {
        $this->config = $this->createAnimationConfig($index);

        $this->updateCSSClasses();

        $this->node->setAttribute('id', $id = $this->createNodeId());

        try {
            $this->appendToRoot(array(
                $this->createPositionObserver($id),
                $this->createAnimationElement(),
                $this->createAnimationStylesElement($index)
            ));
        } catch (\Exception $exception) {
            // Silently skip this animation element.
        }

        return $this;
    }

    /**
     * @param \DOMElement[] $elements
     */
    private function appendToRoot(array $elements)
    {
        foreach ($elements as $element) {
            $this->rootElement->appendChild($element);
        }
    }

    /**
     * @param int $index Index of node.
     * @throws \Exception
     * @return array Animation config.
     */
    protected function createAnimationConfig($index)
    {
        $config = array();

        $config['id']       = $this->generateUniqueClassForAnimation($index);
        $config['selector'] = '.' . $config['id'];
        $config['duration'] = (float) $this->node->getAttribute('data-anim-duration') * 1000;
        $config['delay']    = (float) $this->node->getAttribute('data-anim-delay') * 1000;

        $config['keyframes'] = $this->buildAndGetKeyframes();

        return $config;
    }

    /**
     * @throws \Exception
     * @return array
     */
    abstract protected function buildAndGetKeyframes();

    /**
     * Update CSS classes for current node.
     * @return $this
     */
    protected function updateCSSClasses()
    {
        $classes  = $this->node->getAttribute('class');
        $classes .= ' stk-anim ' . $this->config['id'];
        $this->node->setAttribute('class', $classes);
        return $this;
    }

    /**
     * @return string Unique id value for HTML attribute.
     */
    protected function createNodeId()
    {
        if ($this->node->hasAttribute('id')) {
            return $this->node->getAttribute('id');
        } else {
            return 'target-' . $this->config['id'];
        }
    }

    /**
     * @param $id string Unique id of DOMElement which will be animated.
     *
     * @throws \RuntimeException If element was not created.
     *
     * @return \DOMElement New element.
     */
    protected function createPositionObserver($id)
    {
        if (!isset($this->config) || !isset($this->config['id'])) {
            throw new \RuntimeException();
        }

        $node = $this->dom->createElement('amp-position-observer');
        $node->setAttribute('on', 'enter:' . $this->config['id'] . '.start;');
        $node->setAttribute('intersection-ratios', '0 0.1');
        $node->setAttribute('layout', 'nodisplay');
        $node->setAttribute('target', $id);
        return $node;
    }

    /**
     * @throws \RuntimeException If element was not created.
     * @return \DOMElement
     */
    protected function createAnimationElement()
    {
        if (!isset($this->config) || !isset($this->config['id'])) {
            throw new \RuntimeException();
        }

        $config = array(
            'fill' => 'both',
            'easing' => 'ease',
            'iterations' => 1,
            'animations' => array($this->config),
        );

        $json = wp_json_encode($config);

        if (!is_string($json)) {
            throw new \RuntimeException();
        }

        $node = $this->dom->createElement('amp-animation');
        $node->setAttribute('id', $this->config['id']);
        $node->setAttribute('layout', 'nodisplay');

        $script = $this->dom->createElement('script');
        $script->setAttribute('type', 'application/json');
        $script->textContent = $json;

        $node->appendChild($script);

        return $node;
    }

    /**
     * Generates <style> element for single animation.
     *
     * @param $index string|int ID of this element on the page.
     *
     * @throws \RuntimeException
     *
     * @return \DOMElement
     */
    protected function createAnimationStylesElement($index)
    {
        if (!isset($this->config)) {
            throw new \RuntimeException();
        }

        $properties = array(
            '--stk-shift-y'  => null,
            '--stk-shift-x'  => null,
            '--stk-zoom'     => null,
            '--stk-rotation' => null,
            '--stk-opacity'  => null,
        );

        foreach ($properties as $name => $value) {
            if (isset($this->config[$name])) {
                $properties[$name] = $this->config[$name];
            } else {
                unset($properties[$name]);
            }
        }

        unset($name, $value);

        $css = '';

        foreach ($properties as $property => $value) {
            $css .= $property . ':' . $value . ';';
        }

        $css = sprintf(
            '.stk-anim.stk-anim-%s {%s}',
            $index,
            $css
        );

        $node = $this->dom->createElement('style');
        $node->setAttribute('type', 'text/css');
        $node->textContent = $css;

        return $node;
    }

    /**
     * @return $this
     */
    protected function cleanup()
    {
        $this->removeAttributes();
        return $this;
    }

    /**
     * @return $this
     */
    protected function removeAttributes()
    {
        foreach ($this->attributesToRemove as $attribute) {
            $this->node->removeAttribute($attribute);
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function cleanupCommon()
    {
        return $this;
    }

    /**
     * @throws \DomainException
     * @return string
     */
    protected function getNodeAnimationName()
    {
        $value = $this->node->getAttribute('data-anim-name');

        if (!$value) {
            throw new \DomainException();
        }

        return $value;
    }

    /**
     * Generates unique CSS class for element.
     *
     * @param $index int Unique index of element.
     * @return string Unique CSS class for element.
     */
    protected function generateUniqueClassForAnimation($index)
    {
        return 'stk-anim-' . $this->getUniqueClassFragment() . absint($index);
    }

    /**
     * @return string
     */
    abstract protected function getUniqueClassFragment();
}
