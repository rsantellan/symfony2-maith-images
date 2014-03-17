<?php

namespace Maith\Common\ImageBundle\Twig;

/**
 * Description of mPriceExtension
 *
 * @author Rodrigo Santellan
 */
class mPriceExtension extends \Twig_Extension
{
  
  public function getFilters() {
    return array(
        new \Twig_SimpleFilter('mPrice', array($this, 'mPriceFilter')),
    );
  }
  
  public function mPriceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
  {
    $price = number_format($number, $decimals, $decPoint, $thousandsSep);
    $price = '$'.$price;
    return $price;
  }

  
  public function getName() {
    return 'maith_m_price';
  }
}


