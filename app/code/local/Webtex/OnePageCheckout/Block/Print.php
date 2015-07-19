<?php
class Webtex_OnePageCheckout_Block_Print extends Mage_Core_Block_Template
{
    public function getOrderId()
    {
        return Mage::getSingleton('customer/session')->getData('order_id');
    }
}
