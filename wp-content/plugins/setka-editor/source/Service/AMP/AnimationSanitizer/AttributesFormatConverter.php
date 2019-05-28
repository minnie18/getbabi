<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

class AttributesFormatConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @inheritdoc
     */
    protected function createNodesList()
    {
        $found = array();
        $nodes = $this->createNodeListWithAnimations();

        foreach ($nodes as $this->node) {
            try {
                $this->getNodeAnimationName();
            } catch (\Exception $exception) {
                $found[] = $this->node;
            }
        }

        return $found;
    }

    /**
     * @inheritdoc
     */
    protected function buildAndGetKeyframes()
    {
        $builder = new KeyframesFromAttributes($this->node);
        return $builder->getKeyframesAsArray();
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueClassFragment()
    {
        return '1';
    }
}
