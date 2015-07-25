<?php
 
class Myintent_Checkout_Block_Onepage_Shipping extends Mage_Checkout_Block_Onepage_Shipping
{
	protected function _construct()
	{       
		parent::_construct();
		if ($this->isCustomerLoggedIn()) {
			$this->getCheckout()->setStepData('shipping', 'allow', true);
		}
	}
}