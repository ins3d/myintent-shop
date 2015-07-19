<?php
class Webtex_OnePageCheckout_Block_Cart extends Mage_Checkout_Block_Cart
{
    public function getCancelText()
    {
        return Mage::app()->getStore()->getConfig('onepagecheckout/step_4/cancelmessage');
    }
}
