<?php

class Webtex_OnePageCheckout_Model_Mysql4_Customfields extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('onepage/customfields', 'id');
    }
}