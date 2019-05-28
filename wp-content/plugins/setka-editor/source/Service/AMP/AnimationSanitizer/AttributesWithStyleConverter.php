<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

use Setka\Editor\Service\AMP\AnimationSanitizer\Traits\ScriptAndStylesNodeListFactoryTrait;

class AttributesWithStyleConverter extends AttributesFormatConverter
{
    use ScriptAndStylesNodeListFactoryTrait;

    /**
     * Return list contains \DOMElement-s with data-anim=true.
     * @return \DOMElement[]
     */
    protected function createNodesList()
    {
        $found = array();
        $nodes = $this->createNodeListWithAnimations();

        foreach ($nodes as $this->node) {
            try {
                $this->getNodeStyle();
            } catch (\Exception $exception) {
                continue;
            }

            try {
                $this->getNodeScript();
            } catch (\Exception $exception) {
                $found[] = $this->node;
            }
        }

        return $found;
    }

    /**
     * @inheritdoc
     */
    protected function cleanup()
    {
        $this->removeNodeStyle();
        return parent::cleanup();
    }

    private function removeNodeStyle()
    {
        $node = $this->getFirstNodeFromList($this->createStyleNodeList($this->getNodeAnimationName()));
        $node->parentNode->removeChild($node);
    }

    /**
     * @throws \Exception
     * @return \DOMElement
     */
    private function getNodeScript()
    {
        return $this->getFirstNodeFromList($this->createScriptNodeList($this->getNodeAnimationName()));
    }

    /**
     * @throws \Exception
     * @return \DOMElement
     */
    private function getNodeStyle()
    {
        return $this->getFirstNodeFromList($this->createStyleNodeList($this->getNodeAnimationName()));
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueClassFragment()
    {
        return '2';
    }
}
