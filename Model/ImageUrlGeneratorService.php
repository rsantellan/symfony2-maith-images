<?php

namespace Maith\Common\ImageBundle\Model;

use Symfony\Component\Routing\RouterInterface;

/**
 * Description of ImageUrlGeneratorService
 *
 * @author Rodrigo Santellan
 */
class ImageUrlGeneratorService{

	private $router;
	private $root_dir;

	function __construct(RouterInterface $router, $rootDir) {
		$this->router = $router;
		$this->root_dir = $rootDir;
	}
	
	public function mImageFilter($image, $width = 400, $height = 400, $type = "t", $inRootDir = false)
	{
		$in_root = 0;
		if($inRootDir){
			$in_root = 2;
		}else{
			if(strpos($image, $this->root_dir) !== FALSE){
				$image = str_replace($this->root_dir, "", $image);
				$in_root = 1;
			}else{
				$aux_path = dirname($this->root_dir);
				if(strpos($image, $aux_path) !== FALSE){
					$image = str_replace($aux_path, "", $image);
					$in_root = 2;
				}
			}    
		}
		$url_data = array("p" => $image, "w"=>$width, "h" => $height, "t" => $type, 'r' => $in_root);
		$url = base64_encode(serialize($url_data));
		return $this->router->generate("maith_common_image_show", array('url' => $url));
	}

	
}
