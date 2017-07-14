<?php

namespace Maith\Common\ImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MaithCommonImageBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function showImageAction(Request $request, $url)
    {
      $logger = $this->get('logger');
      $data = unserialize(base64_decode($url));
      $logger->debug('Show Image Action, data string is', $data);
      $image = $data['p'];
      $width = $data['w'];
      $height = $data['h'];
      $type = $data['t'];
      $in_root = $data['r'];
      $imageService = $this->get('maith_common_image.image.mimage');
      $root_dir = $this->get('kernel')->getRootDir();
      if($in_root == 1)
      {
        $image = $root_dir.$image;
      }
      else
      {
        if($in_root == 2)
        {
          $aux_path = dirname($root_dir);
              if(strpos($image, DIRECTORY_SEPARATOR) !== 0)
              {
                  $image = DIRECTORY_SEPARATOR . $image;
              }
          $image = $aux_path.$image;
        }
      }
      $return = "";
      $response = new Response();
      try{
        switch ($type) {
          case "t":
            $return = $imageService->doThumbnail($image, $width, $height);
            break;
          case "ot":
            $return = $imageService->doOutboundThumbnail($image, $width, $height);
            break;
          case "rce":
            $return = $imageService->doResizeCropExact($image, $width, $height);
            break;
          case "mpr":
            $return = $imageService->doMaximunPosibleResize($image, $width, $height);
            break;        
          case "rcce":
            $return = $imageService->doResizeCenterCropExact($image, $width, $height);
            break;
          default:
            $logger->debug('Retrieving original image -------->>');
            $return = $imageService->retrieveOriginal($image);
            break;
        }        
      }catch(\Exception $e){
        $this->get('logger')->error($e->getMessage(), [$e]);
        $response->setStatusCode(404);
        return $response;
      }
      $return_info = pathinfo($return);
      $last_modified_time = filemtime($return);
      $modifiedDateTime = new \DateTime();
      $modifiedDateTime->setTimestamp($last_modified_time);
      
      
      $response->setPublic();
      $expireDateTime = new \DateTime();
      $expireDateTime->add(new \DateInterval('P1M'));
      $response->setExpires($expireDateTime);
      $response->setLastModified($modifiedDateTime);
      $response->setEtag($url);
      if($response->isNotModified($request))
      {
        $response->setStatusCode(304);
        return $response;
      }
      $contentType = 'image/'.$return_info["extension"];
      $code = 200;
      $response->headers->add(array('Content-Type' => $contentType));
      $response->setContent(file_get_contents($return));
      $response->setStatusCode($code);
      return $response;
    }
}
