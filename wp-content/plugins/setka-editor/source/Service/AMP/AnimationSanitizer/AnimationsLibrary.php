<?php
namespace Setka\Editor\Service\AMP\AnimationSanitizer;

class AnimationsLibrary
{
    /**
     * @var array
     */
    private $names = array();

    /**
     * @var array
     */
    private $keyframes = array();

    /**
     * @param $name
     * @return $this
     */
    public function addName($name)
    {
        $this->names[$name] = null;
        return $this;
    }

    /**
     * @param $id string
     * @param array $keyframes
     * @return $this
     */
    public function addAnimationKeyframes($id, array $keyframes)
    {
        $this->addName($id);
        $this->keyframes[$id] = $keyframes;
        return $this;
    }

    /**
     * @param $id string
     * @throws \OutOfRangeException
     * @return array
     */
    public function getAnimationKeyframes($id)
    {
        if (isset($this->keyframes[$id])) {
            return $this->keyframes[$id];
        }
        throw new \OutOfRangeException();
    }

    /**
     * @return array List of animations IDs.
     */
    public function getAnimationsIds()
    {
        return array_keys($this->keyframes);
    }
}
