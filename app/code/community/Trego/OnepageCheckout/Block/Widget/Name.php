<?php
class Trego_OnepageCheckout_Block_Widget_Name extends Mage_Customer_Block_Widget_Name
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('onepagecheckout/widget/name.phtml');
    }
}
