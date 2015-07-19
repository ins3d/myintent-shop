<?php
class Webtex_OnePageCheckout_Block_Review extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getReviewText()
    {
        return Mage::app()->getStore()->getConfig('onepagecheckout/step_4/message');
    }
    
    public function getCancelText()
    {
        return Mage::app()->getStore()->getConfig('onepagecheckout/step_4/cancelmessage');
    }
}
