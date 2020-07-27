<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Block\Info;

class Estimate extends \Magento\Payment\Block\Info {
  protected $_information;
  protected $_template = 'RTech_Quote::estimate/information.phtml';

  public function getInstructions() {
    if ($this->_information === null) {
      $this->_information = $this->getInfo()->getAdditionalInformation(
        'information'
      ) ?: trim($this->getMethod()->getConfigData('information'));
    }
    return $this->_information;
  }
}