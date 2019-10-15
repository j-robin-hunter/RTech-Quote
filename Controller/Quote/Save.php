<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Controller\Quote;

use RTech\Quote\Api\Data\QuoteInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SortOrder;

class Save extends \Magento\Framework\App\Action\Action {

  protected $_storeManager;
  protected $_customerSession;
  protected $_checkoutSession;
  protected $_quoteRepository;
  protected $_savedQuoteFactory;
  protected $_savedQuoteRepository;
  protected $_messageManager;
  protected $_logger;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Checkout\Model\Session $checkoutSession,
    \Magento\Quote\Model\QuoteRepository $quoteRepository,
    \RTech\Quote\Model\QuoteFactory $savedQuoteFactory,
    \RTech\Quote\Model\QuoteRepository $savedQuoteRepository,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Psr\Log\LoggerInterface $logger
  ) {
    parent::__construct($context);
    $this->_storeManager = $storeManager;
    $this->_customerSession = $customerSession;
    $this->_checkoutSession = $checkoutSession;
    $this->_quoteRepository = $quoteRepository;
    $this->_savedQuoteFactory = $savedQuoteFactory;
    $this->_savedQuoteRepository = $savedQuoteRepository;
    $this->_messageManager = $messageManager;
    $this->_logger = $logger;
  }


  public function execute() {
    $postData = $this->getRequest()->getPostValue();
    $quote = $this->_checkoutSession->getQuote();
    $this->_quoteRepository->save($quote);

    if ($this->_customerSession->isLoggedIn()) {
      $result = 'ok';
      try {
        $savedQuote = $this->_savedQuoteFactory->create();
        $savedQuote->setData([
          QuoteInterface::QUOTE_ID => $quote->getId(),
          QuoteInterface::CUSTOMER_ID => $this->_customerSession->getId(),
          QuoteInterface::LINE_ITEMS => count($postData['items']),
          QuoteInterface::GRAND_TOTAL => $postData['subtotal_with_discount'] + $postData['shipping_amount'] + $postData['tax_amount'],
          QuoteInterface::SUBTOTAL => $postData['subtotal_with_discount'],
          QuoteInterface::SHIPPING_AMOUNT => $postData['shipping_amount'],
          QuoteInterface::TAX_AMOUNT => $postData['tax_amount'],
          QuoteInterface::UPDATED_AT => date_create('now')->format('Y-m-d H:i:s')
        ]);
        $this->_savedQuoteRepository->save($savedQuote);
        $this->_eventManager->dispatch('saved_quote_saved_after', ['quote' => $quote]);

        $this->_checkoutSession->clearQuote();
        $quote->setIsActive(0);
        $this->_quoteRepository->save($quote);

      } catch (\Exception $ex) {
        $result = 'error: ' . $ex->getMessage();
        $this->_messageManager->addError(__('Unexpected error: %1 ', $ex->getMessage()));

      }
    } else {
      $result = 'error: ' . __('Unable to save as your login session has expired');
    }
    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);
    return $resultPage->setData(['status' => $result, 'id' => $quote->getId()]);
  }
}