<?php

class Webtex_OnePageCheckout_Model_Mysql4_Order_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('onepage/customfields_order');
        parent::_construct();
    }
}