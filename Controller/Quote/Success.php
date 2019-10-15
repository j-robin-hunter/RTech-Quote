<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Controller\Quote;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use RTech\Payment\Api\Data\TermsInterface;

class Success extends \Magento\Framework\App\Action\Action {

  protected $_messageManager;

  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Message\ManagerInterface $messageManager
  ) {
    $this->_messageManager = $messageManager;
    parent::__construct($context);
  }

  public function execute() {
    $quoteid = $this->getRequest()->getParam('id');
    $this->_messageManager->addSuccess(__('Cart has been saved as quote #%1.',$quoteid));
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    return $resultRedirect->setPath('customer/account');
  }
}