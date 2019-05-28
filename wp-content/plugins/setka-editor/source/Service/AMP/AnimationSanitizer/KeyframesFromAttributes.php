<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

class KeyframesFromAttributes
{
    /**
     * @var \DOMElement
     */
    private $node;

    /**
     * KeyframesFromAttributes constructor.
     *
     * @param \DOMElement $node
     */
    public function __construct(\DOMElement $node)
    {
        $this->node = $node;
    }

    /**
     * @return array
     */
    public function getKeyframesAsArray()
    {
        return array(
            array(
                'opacity' => $this->getOpacity(),
                'transform' => $this->getTransform(),
            ),
            array(
                'opacity' => 1,
                'transform' => 'none',
            ),
        );
    }

    /**
     * @return string
     */
    private function getTransform()
    {
        $transforms = array(
            'translate' => $this->getTranslate(),
            'rotate' => $this->getRotate(),
            'scale' => $this->getScale(),
        );
        return $this->transformsToString($transforms);
    }

    /**
     * @param array $transforms
     * @return string
     */
    private function transformsToString(array &$transforms)
    {
        $result = '';
        foreach ($transforms as $name => $value) {
            $result .= $name . '(' . $value . ') ';
        }
        return $result;
    }

    /**
     * @return float|int
     */
    private function getOpacity()
    {
        return (float) $this->node->getAttribute('data-anim-opacity') / 100;
    }

    /**
     * @return string
     */
    private function getTranslate()
    {
        $direction = $this->node->getAttribute('data-anim-direction');
        $shift     = (int) $this->node->getAttribute('data-anim-shift');

        if ('bottom' === $direction || 'right' === $direction) {
            $shift = $shift * -1;
        }

        $shift = (string) $shift . 'px';

        if ('top' === $direction || 'bottom' === $direction) {
            $y = $shift;
            $x = '0px';
        } else {
            $x = $shift;
            $y = '0px';
        }

        return $x . ',' . $y;
    }

    /**
     * @return string
     */
    private function getRotate()
    {
        return (float) $this->node->getAttribute('data-anim-rotation') . 'deg';
    }

    /**
     * @return float|int
     */
    private function getScale()
    {
        return (float) $this->node->getAttribute('data-anim-zoom') / 100;
    }
}
