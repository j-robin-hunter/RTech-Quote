<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Block\Form;

class Estimate extends \Magento\Payment\Block\Form {

  protected $_template = 'RTech_Quote::form/estimate.phtml';
  protected $_information;

  public function getInstructions() {
    if ($this->_information === null) {
      $method = $this->getMethod();
      $this->_information = $method->getConfigData('information');
    }
    return $this->_information;
  }
}