<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface QuoteSearchResultsInterface extends SearchResultsInterface {

  /**
  * Get quotes
  *
  * @return \RTech\Quote\Api\Data\QuoteInterface[]
  */
  public function getItems();

  /**
  * Set quotes
  *
  * @param \RTech\Quote\Api\Data\QuoteInterface[] $items
  * @return $this
  */
  public function setItems(array $items);
}