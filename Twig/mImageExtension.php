<?php

namespace Maith\Common\ImageBundle\Twig;

use Maith\Common\ImageBundle\Model\MyImageService;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of mImageExtension
 *
 * @author rodrigo
 */
class mImageExtension extends \Twig_Extension
{
  
  private $router;
  private $root_dir;
  
  function __construct(RouterInterface $router, $rootDir) {
    $this->router = $router;
    $this->root_dir = $rootDir;
  }

  
  public function getFilters() {
    return array(
        new \Twig_SimpleFilter('mImage', array($this, 'mImageFilter'))
    );
  }
  
  public function mImageFilter($image, $width = 400, $height = 400, $type = "t", $inRootDir = false)
  {
    $in_root = 0;
    if($inRootDir)
    {
        $in_root = 2;
    }
    else
    {
        if(strpos($image, $this->root_dir) !== FALSE)
        {
          $image = str_replace($this->root_dir, "", $image);
          $in_root = 1;
        }
        else
        {
          $aux_path = dirname($this->root_dir);
          if(strpos($image, $aux_path) !== FALSE)
          {
            $image = str_replace($aux_path, "", $image);
            $in_root = 2;
          }
        }    
    }
    $url_data = array("p" => $image, "w"=>$width, "h" => $height, "t" => $type, 'r' => $in_root);
	/*
    var_dump($url_data);
    var_dump(strpos($image, $this->root_dir) !== FALSE);
    var_dump($this->root_dir);
    var_dump(dirname($this->root_dir));
    */
    $url = base64_encode(serialize($url_data));
    return $this->router->generate("maith_common_image_show", array('url' => $url));
    
  }

  public function getName() {
    return "maith_m_image";
  }
}

