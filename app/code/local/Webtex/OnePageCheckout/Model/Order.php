<?php

class Webtex_OnePageCheckout_Model_Order extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('onepage/customfields_order');
        parent::_construct();
    }

}
