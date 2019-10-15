<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Controller\Quote;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends \Magento\Framework\App\Action\Action {

  protected $_quoteRepository;
  protected $_checkoutSession;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Quote\Model\QuoteRepository $quoteRepository,
    \Magento\Checkout\Model\Session $checkoutSession
  ) {
    parent::__construct($context);
    $this->_quoteRepository = $quoteRepository;
    $this->_checkoutSession = $checkoutSession;
  }

  public function execute() {

    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);

    $postData = $this->getRequest()->getPostValue();
    try {
      if (!empty($postData)) {
        $quote = $this->_quoteRepository->get($postData['quote_id']);
        $this->_quoteRepository->delete($quote);
        // If this is the current quote then remove all items otherwise delete it
        if ($postData['quote_id'] == $this->_checkoutSession->getQuote()->getId()) {
		      $quote->removeAllItems();
          $this->_quoteRepository->save($quote);
        } else {

        }
        $result = 'ok';
      } else {
        throw new \Exception('No quote ID');
      }
    } catch (\Exception $e) {
      $result = $e->getMessage();
    }
    return $resultPage->setData(['status' => $result]);
  }
}