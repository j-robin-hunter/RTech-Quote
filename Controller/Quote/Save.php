<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Controller\Quote;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SortOrder;

class Save extends \Magento\Framework\App\Action\Action {

  protected $_checkoutSession;
  protected $_customerRepository;
  protected $_addressFactory;
  protected $_quoteManagement;
  protected $_messageManager;
  protected $_logger;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Checkout\Model\Session $checkoutSession,
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
    \Magento\Customer\Model\AddressFactory $addressFactory,
    \Magento\Quote\Model\QuoteManagement $quoteManagement,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Psr\Log\LoggerInterface $logger
  ) {
    parent::__construct($context);
    $this->_checkoutSession = $checkoutSession;
    $this->_customerRepository = $customerRepository;
    $this->_addressFactory = $addressFactory;
    $this->_quoteManagement = $quoteManagement;
    $this->_messageManager = $messageManager;
    $this->_logger = $logger;
  }

  public function execute() {
    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

    try {
      $params = $this->getRequest()->getParams();
      $quote = $this->_checkoutSession->getQuote();

      $customer = $this->_customerRepository->getById($quote->getCustomerId());

      $addressData = [
        'firstname' => $customer->getFirstname(),
        'lastname' => $customer->getLastname(),
        'street' => __('Street not provided'),
        'city' => __('City not provided'),
        'region' => $params['region'],
        'region_id' => $params['regionId'],
        'postcode' => $params['postcode'],
        'telephone' => __(' '),
        'country_id' => $params['countryId']
      ];

      $quote->getShippingAddress()->addData($addressData);

      $billingAddressId = $customer->getDefaultBilling();
      $billingAddress = $this->_addressFactory->create()->load($billingAddressId);
      $addressData['street'] = $billingAddress->getStreet();
      $addressData['city'] = $billingAddress->getCity();
      $addressData['region'] = $billingAddress->getRegion();
      $addressData['region_id'] = $billingAddress->getRegionId();
      $addressData['postcode'] = $billingAddress->getPostcode();
      $addressData['telephone'] = $billingAddress->getTelephone();
      $addressData['country_id'] = $billingAddress->getCountryId();

      $quote->getBillingAddress()->addData($addressData);

      $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates()->setShippingMethod($params['shippingMethod']);
      $quote->setPaymentMethod('estimate');
      $quote->setInventoryProcessed(false);
      $quote->save();

      $quote->getPayment()->importData(['method' => 'estimate']);
      $quote->collectTotals()->save();

      $order = $this->_quoteManagement->submit($quote);
      $order-> addCommentToStatusHistory(
        __('While we make every effort to maintain our pricing and unless otherwise stated, this self generated estimate is only valid at this time.'),
        false, 
        true);
      $order->save();
      $this->_checkoutSession->clearQuote();
      $quote->setIsActive(0);
      $resultPage->setPath('sales/order/view', ['order_id' => $order->getEntityId()]);
    } catch (\Exception $e) {
      $this->_messageManager->addError(__('Unexpected error creating estimate: %1', $e->getMessage()));
      $resultPage->setPath('customer/account');
    }
    return $resultPage;
  }
}