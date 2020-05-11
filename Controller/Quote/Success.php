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
  protected $_cart;

  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Checkout\Model\Cart $cart
  ) {
    $this->_messageManager = $messageManager;
    $this->_cart = $cart;
    parent::__construct($context);
  }

  public function execute() {
    $quoteid = $this->getRequest()->getParam('id');
    $this->_messageManager->addSuccess(__('Cart has been saved as quote #%1.',$quoteid));
    try {
      $this->_cart->truncate()->save();
    } catch (\Magento\Framework\Exception\LocalizedException $exception) {
        $this->_messageManager->addErrorMessage($exception->getMessage());
    } catch (\Exception $exception) {
        $this->_messageManager->addExceptionMessage($exception, __('We can\'t update the shopping cart.'));
    }
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    return $resultRedirect->setPath('customer/account');
  }
}