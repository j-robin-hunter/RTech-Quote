<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use RTech\Quote\Api\Data\QuoteInterface;

class Quote extends AbstractModel implements IdentityInterface, QuoteInterface {

  const CACHE_TAG = 'rtech_quote';

  protected $_cacheTag = self::CACHE_TAG;
  protected $_eventPrefix = self::CACHE_TAG;

  protected function _construct() {
    $this->_init(\RTech\Quote\Model\ResourceModel\Quote::class);
  }

  /**
  * Return unique ID(s)
  *
  * @return string[]
  */
  public function getIdentities() {
    return [self::CACHE_TAG . '_' . $this->getId()];
  }

  /**
  * @inheritdoc
  */
  public function getId() {
    return $this->getData(self::QUOTE_ID);
  }

  /**
  * @inheritdoc
  */
  public function setId($quoteId) {
    return $this->setData(self::QUOTE_ID, $quoteId);
  }

  /**
  * @inheritdoc
  */
  public function getCustomerId() {
    return $this->getData(self::CUSTOMER_ID);
  }

  /**
  * @inheritdoc
  */
  public function setCustomerId($customerId) {
    return $this->setData(self::CUSTOMER_ID, $customerId);
  }

  /**
  * @inheritdoc
  */
  public function getLineItems() {
    return $this->getData(self::LINE_ITEMS);
  }

  /**
  * @inheritdoc
  */
  public function setLineItems($lineItems) {
    return $this->setData(self::LINE_ITEMS, $lineItems);
  }

  /**
  * @inheritdoc
  */
  public function getGrandTotal() {
    return $this->getData(self::GRAND_TOTAL);
  }

  /**
  * @inheritdoc
  */
  public function setGrandTotal($grandTotal) {
    return $this->setData(self::GRAND_TOTAL, $grandTotal);
  }

  /**
  * @inheritdoc
  */
  public function getSubtotal() {
    return $this->getData(self::SUBTOTAL);
  }

  /**
  * @inheritdoc
  */
  public function setSubtotal($subTotal) {
    return $this->setData(self::SUBTOTAL, $subTotal);
  }

  /**
  * @inheritdoc
  */
  public function getShippingAmount() {
    return $this->getData(self::SHIPPING_AMOUNT);
  }

  /**
  * @inheritdoc
  */
  public function setShippingAmount($shippingAmount) {
    return $this->setData(self::SHIPPING_AMOUNT, $shippingAmount);
  }

  /**
  * @inheritdoc
  */
  public function getTaxAmount() {
    return $this->getData(self::TAX_AMOUNT);
  }

  /**
  * @inheritdoc
  */
  public function setTaxAmount($taxAmount) {
    return $this->setData(self::TAX_AMOUNT, $taxAmount);
  }

  /**
  * @inheritdoc
  */
  public function getEstimateId() {
    return $this->getData(self::ESTIMATE_ID);
  }

  /**
  * @inheritdoc
  */
  public function setEstimateId($estimateId) {
    return $this->setData(self::ESTIMATE_ID, $estimateId);
  }

  /**
  * @inheritdoc
  */
  public function getCreatedAt() {
    return $this->getData(self::CREATED_AT);
  }

  /**
  * @inheritdoc
  */
  public function setCreatedAt($date) {
    return $this->setData(self::CREATED_AT, $date);
  }

  /**
  * @inheritdoc
  */
  public function getUpdatedAt() {
    return $this->getData(self::UPDATED_AT);
  }

   /**
  * @inheritdoc
  */
  public function setUpdatedAt($date) {
    return $this->setData(self::UPDATED_AT, $date);
  }

}