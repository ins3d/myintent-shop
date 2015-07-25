<?php
 
class Myintent_Checkout_Block_Onepage_Billing extends Mage_Checkout_Block_Onepage_Billing
{
	protected function _construct()
	{   
		parent::_construct();   
		$this->getCheckout()->setStepData('billing', 'allow', false); 
	}
}