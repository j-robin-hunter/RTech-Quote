<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Helper;

class LoggedIn extends \Magento\Framework\App\Helper\AbstractHelper {

  public function __construct(
    \Magento\Framework\App\Helper\Context $context,
    \Magento\Customer\Model\Session $customerSession
  ) {
    $this->customerSession = $customerSession;
    parent::__construct($context);
  }

  public function isLoggedIn() {
    return $this->customerSession->isLoggedIn();
  }
}