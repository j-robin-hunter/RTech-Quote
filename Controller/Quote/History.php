<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Controller\Quote;

use Magento\Framework\Controller\ResultFactory;

class History extends \Magento\Framework\App\Action\Action {

  public function __construct(
    \Magento\Framework\App\Action\Context $context
  ) {
    parent::__construct($context);
  }

  public function execute() {
    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    $block = $resultPage->getLayout()->getBlock('quote.quote.history');
    return $resultPage;
  }
}