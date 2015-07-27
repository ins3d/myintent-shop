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
	
	// from Mage_Checkout_Block_Onepage_Abstract
	public function getCountryHtmlSelect($type)
    {
        $countryId = $this->getAddress()->getCountryId();
        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
//            $select->setExtraParams('onchange="if(window.shipping)shipping.setSameAsBilling(false);"');
            $select->setExtraParams('onchange="if(window.billing)billing.setSameAsShipping();"');
        }

        return $select->getHtml();
    }
}