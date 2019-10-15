<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Api\Data;

interface QuoteInterface {
  const QUOTE_ID = 'quote_id';
  const CUSTOMER_ID = 'customer_id';
  const LINE_ITEMS = 'line_items';
  const GRAND_TOTAL = 'grand_total';
  const SUBTOTAL = 'subtotal';
  const SHIPPING_AMOUNT = 'shipping_amount';
  const TAX_AMOUNT = 'tax_amount';
  const ESTIMATE_ID = 'estimate_id';
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';
  /**
  * Get quote id
  *
  * @return int|null
  */
  public function getId();

  /**
  * Set quote id
  *
  * @param int $quoteId
  * @return $this
  */
  public function setId($quoteId);

  /**
  * Get Magento customer id
  *
  * @return int
  */
  public function getCustomerId();

  /**
  * Set Magento customer id
  *
  * @param int $customerId
  * @return $this
  */
  public function setCustomerId($customerId);

  /**
  * Get quote line item count
  *
  * @return int
  */
  public function getLineItems();

  /**
  * Set quote line items count
  *
  * @param int $lineItems
  * @return $this
  */
  public function setLineItems($lineItems);

  /**
  * Get quote grand total
  *
  * @return float
  */
  public function getGrandTotal();

  /**
  * Set quote grand total
  *
  * @param float $grandTotal
  * @return $this
  */
  public function setGrandTotal($grandTotal);

  /**
  * Get quote subtotal
  *
  * @return float
  */
  public function getSubtotal();

  /**
  * Set quote subtotal
  *
  * @param float $subTotal
  * @return $this
  */
  public function setSubtotal($subTotal);

  /**
  * Get quote shipping amount
  *
  * @return float
  */
  public function getShippingAmount();

  /**
  * Set quote shipping amount
  *
  * @param float $shippingAmount
  * @return $this
  */
  public function setShippingAmount($shippingAmount);

  /**
  * Get quote tax amount
  *
  * @return float
  */
  public function getTaxAmount();

  /**
  * Set quote tax amount
  *
  * @param float $taxAmount
  * @return $this
  */
  public function setTaxAmount($tTaxAmount);

  /**
  * Get Zoho estimate id
  *
  * @return string
  */
  public function getEstimateId();

  /**
  * Set Zoho estimate id
  *
  * @param string $estimateId
  * @return $this
  */
  public function setEstimateId($estimateId);

  /**
  * Get created at date
  *
  * @return Date
  */
  public function getCreatedAt();

  /**
  * Set create at date
  *
  * @param Date $date
  * @return $this
  */
  public function setCreatedAt($date);

  /**
  * Get update at date
  *
  * @return Date
  */
  public function getUpdatedAt();

  /**
  * Set update at date
  *
  * @param Date $date
  * @return $this
  */
  public function setUpdatedAt($date);
}