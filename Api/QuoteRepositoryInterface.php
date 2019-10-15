<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Api;

use RTech\Quote\Api\Data\QuoteInterface;

interface QuoteRepositoryInterface {

  /**
  * Retrive by quote id
  *
  * @param int $quoteId
  * @return \RTech\Quote\Api\Data\QuoteInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function getById($quoteId);

  /**
  * @param \RTech\Quote\Api\Data\QuoteInterface $quote
  * @return \RTech\Quote\Api\Data\QuoteInterface
  * @throws \Magento\Framework\Exception\CouldNotSaveException
  */
  public function save(QuoteInterface $quote);

  /**
  * @param \RTech\Quote\Api\Data\QuoteInterface $quote
  * @return bool true on success
  * @throws \Magento\Framework\Exception\CouldNotDeleteException
  */
  public function delete(QuoteInterface $quote);

  /**
  * Retrieve quotes matching the specified criteria.
  *
  * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
  * @return \RTech\Quote\Api\Data\QuoteSearchResultsInterface
  * @throws \Magento\Framework\Exception\LocalizedException
  */
  public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}