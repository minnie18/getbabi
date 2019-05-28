<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

use Setka\Editor\Service\AMP\AnimationSanitizer\Traits\ScriptAndStylesNodeListFactoryTrait;

class WAAPIConverter extends AbstractConverterWithLibrary
{
    use ScriptAndStylesNodeListFactoryTrait;

    /**
     * @inheritdoc
     */
    protected function createNodesList()
    {
        $found = array();
        $nodes = $this->createNodeListWithAnimations();

        foreach ($nodes as $this->node) {
            try {
                $this->getNodeScript();
                $found[] = $this->node;
            } catch (\Exception $exception) {
                continue;
            }
        }

        return $found;
    }

    /**
     * @inheritdoc
     */
    protected function buildAndGetKeyframes()
    {
        try {
            return $this->animations->getAnimationKeyframes($this->getNodeAnimationName());
        } catch (\Exception $exception) {
            return $this->cacheKeyframes($this->decodeKeyframes($this->getNodeScript()));
        }
    }

    /**
     * @param array $keyframes
     * @return array
     */
    private function cacheKeyframes(array $keyframes)
    {
        $this->animations->addAnimationKeyframes($this->getNodeAnimationName(), $keyframes);
        return $keyframes;
    }

    /**
     * @param \DOMElement $script
     * @throws \DomainException
     * @return array
     */
    private function decodeKeyframes(\DOMElement $script)
    {
        $config = json_decode($script->textContent, true);

        if (!is_array($config)) {
            throw new \DomainException(json_last_error_msg());
        }

        if (!isset($config['keyframes'])) {
            throw new \DomainException('No keyframes in configuration.');
        }
        return $config['keyframes'];
    }

    /**
     * @inheritdoc
     */
    protected function cleanupCommon()
    {
        $ids = $this->animations->getAnimationsIds();
        foreach ($ids as $id) {
            try {
                $this->deleteScript($this->getFirstNodeFromList($this->createScriptNodeList($id)));
            } catch (\Exception $exception) {
            }
        }

        parent::cleanupCommon();
        return $this;
    }

    /**
     * @param $script \DOMElement
     * @return $this
     */
    private function deleteScript(\DOMElement $script)
    {
        $script->parentNode->removeChild($script);
        return $this;
    }

    /**
     * @throws \DomainException
     * @return \DOMElement
     */
    private function getNodeScript()
    {
        return $this->getFirstNodeFromList($this->createScriptNodeList($this->getNodeAnimationName()));
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueClassFragment()
    {
        return '3';
    }
}
