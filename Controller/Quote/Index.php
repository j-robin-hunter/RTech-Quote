<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Controller\Quote;

use RTech\Quote\Api\Data\QuoteInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action {

  protected $_checkoutSession;
  protected $_quoteRepository;
  protected $_savedQuoteRepository;
  protected $_currencyFactory;
  protected $_messageManager;
  protected $_logger;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Checkout\Model\Session $checkoutSession,
    \Magento\Quote\Model\QuoteRepository $quoteRepository,
    \RTech\Quote\Model\QuoteRepository $savedQuoteRepository,
    \Magento\Directory\Model\CurrencyFactory $currencyFactory,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Catalog\Helper\Product $catalogProduct,
    \Psr\Log\LoggerInterface $logger
  ) {
    parent::__construct($context);
    $this->_checkoutSession = $checkoutSession;
    $this->_quoteRepository = $quoteRepository;
    $this->_savedQuoteRepository = $savedQuoteRepository;
    $this->_currencyFactory = $currencyFactory;
    $this->_messageManager = $messageManager;
    $this->_logger = $logger;
  }


  public function execute() {
    $quoteid = $this->getRequest()->getParam('id');
    $cart = $this->_checkoutSession->getQuote();
    $cartIdId = $cart->getId();

    // Delete any easy to identify null quote
    if ($cart->getItemsCount() == 0) {
      $this->_quoteRepository->delete($cart);
    }

    try {
      $savedQuote = $this->_savedQuoteRepository->getById($quoteid);
      $savedPrice = $savedQuote->getSubtotal();
      $savedDate = $savedQuote->getUpdatedAt();

      $cart = $this->_quoteRepository->get($quoteid);
      $cartPrice = $cart->getSubtotalWithDiscount();

      if ($savedPrice < $cartPrice) {
        $this->_messageManager->addNotice(__('Quote #%1 has been restored. We are sorry, but recent price changes have increased you order subtotal by %2',
        $quoteid, $this->getPriceDifference($cart, $savedPrice)));
      } elseif ($savedPrice > $cartPrice) {
        $this->_messageManager->addSuccess(__('Quote #%1 has been updated with recent lower prices, saving you %2 off your subtotal',
          $quoteid, $this->getPriceDifference($cart, $savedPrice)));
      } else {
        if ($cartIdId != $quoteid) {
          $this->_messageManager->addSuccess(__('Quote #%1 has been restored', $quoteid));
        }
      }

      $cart->setIsActive(true);
      $cart->setTriggerRecollect(true);
      $this->_quoteRepository->save($cart);
      $this->_checkoutSession->replaceQuote($cart);
    } catch (\Exception $e) {
      $this->_messageManager->addError(__('An unexpected error has occured while restoring quote #%1', $quoteid));
    }

    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    $resultPage->setPath('checkout/cart');
    return $resultPage;
  }

  private function getPriceDifference($quote, $savedPrice) {
    return $this->_currencyFactory->create()->load($quote->getQuoteCurrencyCode())->format(abs($quote->getSubtotal() - $savedPrice));
  }

}