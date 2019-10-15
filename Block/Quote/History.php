<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Block\Quote;

use \Magento\Framework\Api\SortOrder;
use \RTech\Energy\Model\ResourceModel\Model\CollectionFactoryInterface;

class History extends \Magento\Framework\View\Element\Template {

  protected $_customerSession;
  protected $_storeManager;
  protected $_checkoutSession;
  protected $_quoteCollectionFactory;
  protected $_quotes;

  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Checkout\Model\Session $checkoutSession,
    \RTech\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
    \Magento\Directory\Model\CurrencyFactory $currencyFactory,
    array $data = []
  ) {
    $this->_storeManager = $storeManager;
    $this->_customerSession = $customerSession;
    $this->_checkoutSession = $checkoutSession;
    $this->_quoteCollectionFactory = $quoteCollectionFactory;
    $this->_currencyFactory = $currencyFactory;
    parent::__construct($context, $data);
  }

  public function getQuotes() {
    if (!($customerId = $this->_customerSession->getCustomerId())) {
      return false;
    }
    if (!$this->_quotes) {
      $this->_quotes = $this->_quoteCollectionFactory->create()
        ->addFieldToFilter('customer_id', array('eq' => $customerId))
        ->setOrder('updated_at', 'desc');
    }
    return $this->_quotes;
  }

  public function quoteTotal($quote) {
    return $this->_currencyFactory->create()->load($this->_storeManager->getStore()->getCurrentCurrency()->getCode())->format($quote->getGrandTotal());
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

  public function getPagerHtml() {
    return $this->getChildHtml('pager');
  }

  public function getViewUrl() {
    return $this->getUrl('quote/quote/index');
  }

  public function getDeletetUrl() {
    return $this->getUrl('quote/quote/delete');
  }

  public function getBackUrl() {
    return $this->getUrl('customer/account/');
  }

  protected function _prepareLayout() {
    parent::_prepareLayout();
    if ($this->getQuotes()) {
      $pager = $this->getLayout()->createBlock(\Magento\Theme\Block\Html\Pager::class, 'quote.history.pager')->setCollection($this->getQuotes());
      $this->setChild('pager', $pager);
      $this->getQuotes()->load();
    }
    return $this;
  }
}