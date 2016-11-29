<?php

namespace Maith\Common\ImageBundle\Model;

/**
 * Description of MyImageManager
 *
 * @author Rodrigo Santellan
 */
interface ImageManagerInterface {

	public function doResizeCropExact($image_path, $width, $height);

	public function doResizeCenterCropExact($image_path, $width, $height);

	public function doMaximunPosibleResize($image_path, $width, $height, $background = 'ffffff');

	public function retrieveOriginal($image_path);

	public function doThumbnail($image_path, $width, $height);

	public function doOutboundThumbnail($image_path, $width, $height);

	public function retrieveCachePath($path, $type, $parameters = array());

	
}
