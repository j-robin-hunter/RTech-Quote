<?php
/**
 * Copyright © 2019 Roma Technology Limited. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace RTech\Quote\Plugin;

class OrderSenderPlugin {

  protected $orderResource;

  public function __construct(
    \Magento\Sales\Model\ResourceModel\Order $orderResource
  ) {
    $this->orderResource = $orderResource;
  }

  public function aroundSend(
    \Magento\Sales\Model\Order\Email\Sender\OrderSender $subject,
    callable $send,
    \Magento\Sales\Model\Order $order,
    bool $forceSyncMode = false
  ) {
    if ($order->getPayment()->getMethod() != 'estimate') {
      return $send($order, $forceSyncMode);
    }
    $order->setEmailSent(true);
    $this->orderResource->saveAttribute($order, ['send_email', 'email_sent']);
    return true;
  }
}