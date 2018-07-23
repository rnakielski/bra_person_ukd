<?php
namespace Cobra3\BraPersonUkd\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;

/**
 * Class UnveilImageViewHelper
 *
 * @package Cobra3\BraContentElements\ViewHelpers
 */
class UnveilImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Tag builder instance $imageTag
     *
     * @var \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder
     * @inject
     * @api
     */
    protected $imageTag = null;

    /**
     * Tag builder instance $noScriptImageTag
     *
     * @var \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder
     * @inject
     * @api
     */
    protected $noScriptImageTag = null;

    /**
     * @var string $imageTagName
     */
    protected $imageTagName = 'img';

    /**
     * @var string $noScriptImageTagName
     */
    protected $noScriptImageTagName = 'img';

    /**
     * @var \TYPO3\CMS\Extbase\Service\ImageService
     * @inject
     */
    protected $imageService;



    /**
     * Sets the tag name to $this->tagName.
     * Additionally, sets all tag attributes which were registered in
     * $this->tagAttributes and additionalArguments.
     *
     * Will be invoked just before the render method.
     *
     * @return void
     * @api
     */
    public function initialize()
    {
        parent::initialize();

        $this->imageTag->reset();
        $this->noScriptImageTag->reset();

        $this->imageTag->setTagName($this->imageTagName);
        $this->noScriptImageTag->setTagName($this->noScriptImageTagName);
    }

    /**
     * Resizes a given image (if required) and renders the respective img tag
     *
     * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4164427
     *
     * @param string                           $src                a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead
     * @param string                           $width              width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string                           $height             height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param int                              $minWidth           minimum width of the image
     * @param int                              $minHeight          minimum height of the image
     * @param int                              $maxWidth           maximum width of the image
     * @param int                              $maxHeight          maximum height of the image
     * @param bool                             $treatIdAsReference given src argument is a sys_file_reference record
     * @param FileInterface|AbstractFileFolder $image              a FAL object
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string Rendered tag
     */
    public function render(
        $src = null, $width = null, $height = null, $minWidth = null, $minHeight = null, $maxWidth = null,
        $maxHeight = null, $treatIdAsReference = false, $image = null
    )
    {
        if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception(
                'You must either specify a string src or a File object.', 1382284106
            );
        }
        $image                  = $this->imageService->getImage($src, $image, $treatIdAsReference);
        $processingInstructions = array(
            'width'     => $width,
            'height'    => $height,
            'minWidth'  => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth'  => $maxWidth,
            'maxHeight' => $maxHeight,
        );
        $processedImage         = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
        $imageUri               = $this->imageService->getImageUri($processedImage);

        $this->imageTag->addAttribute('class', 'unveil__img');

        // @todo via parameter loesen
        $this->imageTag->addAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');

        $this->imageTag->addAttribute('data-src', $imageUri);
        $this->imageTag->addAttribute('width', $processedImage->getProperty('width'));
        $this->imageTag->addAttribute('height', $processedImage->getProperty('height'));

        $this->noScriptImageTag->addAttribute('src', $imageUri);
        $this->noScriptImageTag->addAttribute('width', $processedImage->getProperty('width'));
        $this->noScriptImageTag->addAttribute('height', $processedImage->getProperty('height'));

        $alt   = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        // The alt-attribute is mandatory to have valid html-code, therefore add it even if it is empty
        if (empty($this->arguments['alt'])) {
            $this->imageTag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && $title) {
            $this->imageTag->addAttribute('title', $title);
        }

        return $this->imageTag->render()
            . '<noscript>' . $this->noScriptImageTag->render() . '</noscript>';
    }
}