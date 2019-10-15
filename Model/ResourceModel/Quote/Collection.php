<?php
/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
namespace RTech\Quote\Model\ResourceModel\Quote;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

  protected $_idFieldName = 'quote_id';

  protected function _construct() {
    $this->_init(\RTech\Quote\Model\Quote::class, \RTech\Quote\Model\ResourceModel\Quote::class);
  }
}