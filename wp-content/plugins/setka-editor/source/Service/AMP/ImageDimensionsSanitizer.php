<?php
namespace Setka\Editor\Service\AMP;

use Setka\Editor\Plugin;

class ImageDimensionsSanitizer extends \AMP_Base_Sanitizer
{
    /**
     * @var \DOMElement Current image.
     */
    protected $currentImg;

    /**
     * @var integer Id of current image (attachment).
     */
    protected $currentImgId;

    /**
     * @inheritdoc
     */
    public function sanitize()
    {
        /**
         * @var $posts \DOMNodeList
         * @var $post \DOMElement
         * @var $images \DOMNodeList
         */
        $xpath = new \DOMXPath($this->dom);
        $posts = $xpath->query('//div[contains(@class, "stk-post")]');

        foreach ($posts as $post) {
            $images = $xpath->query('.//img', $post);

            foreach ($images as $image) {
                if (!is_a($image, \DOMElement::class)) {
                    continue;
                }

                $this
                    ->setCurrentImg($image)
                    ->removeStkReset();

                try {
                    $this
                        ->detectCurrentImageId()
                        ->widthAndHeightAttributes()
                        ->srcSet();
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }
    }

    /**
     * Detect current img id.
     *
     * ID stored in data-image-id attribute if post created in Setka Editor 2 version, otherwise in id.
     *
     * @throws \RuntimeException If id not found.
     *
     * @return $this
     */
    public function detectCurrentImageId()
    {
        if ($this->getCurrentImg()->hasAttribute('data-image-id')) {
            $id = $this->getCurrentImg()->getAttribute('data-image-id');
        } else {
            $idAttr = $this->getCurrentImg()->getAttribute('id');
            $id     = filter_var($idAttr, FILTER_SANITIZE_NUMBER_INT); // attribute format id='image-1325'
        }

        if (!is_string($id) || empty($id)) {
            throw new \RuntimeException();
        }

        $id = absint($id);

        if ($id <= 0) {
            throw new \RuntimeException();
        }

        $this->currentImgId = $id;

        return $this;
    }

    /**
     * Remove stk-reset CSS class.
     *
     * @return $this
     */
    public function removeStkReset()
    {
        $node    = $this->currentImg;
        $classes = trim($node->getAttribute('class'));
        if (!empty($classes)) {
            $classes = explode(' ', $classes);

            if (!empty($classes)) {
                $index = array_search('stk-reset', $classes, true);

                if (false !== $index) {
                    unset($classes[$index]);

                    if (empty($classes)) {
                        $node->removeAttribute('class');
                    } else {
                        $node->setAttribute('class', implode(' ', $classes));
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Add width and height attributes for img.
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function widthAndHeightAttributes()
    {
        if (!isset($this->currentImgId)) {
            throw new \RuntimeException();
        }

        $node = $this->currentImg;

        if ($node->hasAttribute('width') || $node->hasAttribute('height')) {
            return $this;
        }

        $meta = wp_get_attachment_metadata($this->currentImgId);

        if (isset($meta['width']) && isset($meta['height'])) {
            $node->setAttribute('width', $meta['width']);
            $node->setAttribute('height', $meta['height']);
        }

        return $this;
    }

    /**
     * Add srcset attribute for img.
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function srcSet()
    {
        if (!isset($this->currentImgId)) {
            throw new \RuntimeException();
        }

        $node = $this->currentImg;

        if ($node->hasAttribute('srcset')) {
            return $this;
        }

        $srcSet = wp_get_attachment_image_srcset($this->currentImgId, Plugin::NAME . '-1000');

        if (is_string($srcSet)) {
            $node->setAttribute('srcset', $srcSet);
        }

        return $this;
    }

    /**
     * @return \DOMElement
     */
    public function getCurrentImg()
    {
        return $this->currentImg;
    }

    /**
     * @param \DOMElement $currentImg
     * @return $this
     */
    public function setCurrentImg(\DOMElement $currentImg)
    {
        $this->currentImg   = $currentImg;
        $this->currentImgId = null;
        return $this;
    }
}
