<?php

namespace Maith\Common\ImageBundle\Twig;

use Maith\Common\ImageBundle\Model\ImageUrlGeneratorService;

/**
 * Description of mImageExtension
 *
 * @author Rodrigo Santellan
 */
class mImageExtension extends \Twig_Extension
{
  
  private $service;
  
  function __construct(ImageUrlGeneratorService $service) {
    $this->service = $service;
  }

  
  public function getFilters() {
    return array(
        new \Twig_SimpleFilter('mImage', array($this, 'mImageFilter'))
    );
  }
  
  public function mImageFilter($image, $width = 400, $height = 400, $type = "t", $inRootDir = false)
  {
	  return $this->service->mImageFilter($image, $width, $height, $type, $inRootDir);
  }

  public function getName() {
    return "maith_m_image";
  }
}

