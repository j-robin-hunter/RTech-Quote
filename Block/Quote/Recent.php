<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Block\Quote;

use \Magento\Framework\Api\SortOrder;

class Recent extends \Magento\Framework\View\Element\Template {

  const QUOTE_LIMIT = 5;

  protected $_customerSession;
  protected $_storeManager;
  protected $_checkoutSession;
  protected $_quoteCollection;
  protected $_currencyFactory;

  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Checkout\Model\Session $checkoutSession,
    \RTech\Quote\Model\ResourceModel\Quote\Collection $quoteCollection,
    \Magento\Directory\Model\CurrencyFactory $currencyFactory,
    array $data = []
  ) {
    $this->_storeManager = $storeManager;
    $this->_customerSession = $customerSession;
    $this->_checkoutSession = $checkoutSession;
    $this->_quoteCollection = $quoteCollection;
    $this->_currencyFactory = $currencyFactory;
    parent::__construct($context, $data);
  }

  public function getQuotes() {

    return $this->_quoteCollection
      ->addFieldToFilter('customer_id', ['eq' => $this->_customerSession->getCustomer()->getId()])
      ->setOrder('updated_at','DESC')
      ->setPageSize(self::QUOTE_LIMIT);
  }

  public function quoteSubtotal($quote) {
    return $this->_currencyFactory->create()->load($this->_storeManager->getStore()->getCurrentCurrency()->getCode())->format($quote->getSubtotal());
  }

  public function quoteTax($quote) {
    return $this->_currencyFactory->create()->load($this->_storeManager->getStore()->getCurrentCurrency()->getCode())->format($quote->getTaxAmount());
  }

  public function quoteShipping($quote) {
    return $this->_currencyFactory->create()->load($this->_storeManager->getStore()->getCurrentCurrency()->getCode())->format($quote->getShippingAmount());
  }

  public function quoteDate($quote) {
    if ($this->_checkoutSession->getQuoteId() == $quote->getId()) {
      return __('Current Cart');
    }
    return $this->formatDate($quote->getUpdatedAt()) . ' ' . $this->formatTime($quote->getUpdatedAt());
  }

  public function currentCartItems() {
    if (empty($this->_checkoutSession->getQuote()->getId())) {
      return 0;
    }
    return $this->_checkoutSession->getQuote()->getItemsCount();
  }

  public function currentCartId() {
    if (empty($this->_checkoutSession->getQuote()->getId())) {
      return 0;
    }
    return $this->_checkoutSession->getQuote()->getId();
  }

  public function getViewUrl() {
    return $this->getUrl('quote/quote/index');
  }

  public function getDeletetUrl() {
    return $this->getUrl('quote/quote/delete');
  }
}