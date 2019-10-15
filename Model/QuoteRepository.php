<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Model;

use RTech\Quote\Api\QuoteRepositoryInterface;
use RTech\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use RTech\Quote\Model\ResourceModel\Quote\Collection;
use RTech\Quote\Api\Data\QuoteSearchResultsInterface;
use RTech\Quote\Api\Data\QuoteSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class QuoteRepository implements QuoteRepositoryInterface {

  protected $quoteFactory;
  protected $quoteCollectionFactory;
  protected $quoteSearchResultsFactory;

  public function __construct(
    QuoteFactory $quoteFactory,
    QuoteCollectionFactory $quoteCollectionFactory,
    QuoteSearchResultsInterfaceFactory $quoteSearchResultsFactory
  ) {
    $this->quoteFactory = $quoteFactory;
    $this->quoteCollectionFactory = $quoteCollectionFactory;
    $this->quoteSearchResultsFactory = $quoteSearchResultsFactory;
  }

  /**
  * @inheritdoc
  */
  public function getById($quoteId) {
    $quote = $this->quoteFactory->create();
    $response = $quote->getResource()->load($quote, $quoteId);
    if (!$quote->getId()) {
      throw new NoSuchEntityException(__('No Quote with id "%1" exists.', $quoteId));
    }
    return $quote;
  }

  /**
  * @inheritdoc
  */
  public function save($quote) {
    try {
      $quote->getResource()->save($quote);
    } catch (\Exception $exception) {
      throw new CouldNotSaveException(__($exception->getMessage()));
    }
  }

  /**
  * @inheritdoc
  */
  public function delete($quote) {
    try {
      return $quote->getResource()->delete($quote);
    } catch (\Exception $exception) {
      throw new CouldNotDeleteException(__($exception->getMessage()));
    }
  }

  /**
  * @inheritdoc
  */
  public function getList(SearchCriteriaInterface $searchCriteria) {
    $collection = $this->quoteCollectionFactory->create();
    $this->addFiltersToCollection($searchCriteria, $collection);
    $collection->setPageSize($searchCriteria->getPageSize());
    $collection->setCurPage($searchCriteria->getCurrentPage());
    $collection->load();
    return $this->buildSearchResult($searchCriteria, $collection);
  }

  private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection) {
    foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
      $fields = $conditions = [];
      foreach ($filterGroup->getFilters() as $filter) {
        $fields[] = $filter->getField();
        $conditions[] = [$filter->getConditionType() => $filter->getValue()];
      }
      $collection->addFieldToFilter($fields, $conditions);
    }

    foreach ($searchCriteria->getSortOrders() as $sortOrder) {
      $collection->setOrder($sortOrder->getField(), $sortOrder->getDirection());
    }
  }

  private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection) {
    $searchResults = $this->quoteSearchResultsFactory->create();
    $searchResults->setSearchCriteria($searchCriteria);
    $searchResults->setItems($collection->getItems());
    $searchResults->setTotalCount($collection->getSize());
    return $searchResults;
  }
}