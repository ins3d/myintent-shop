<?php
class Webtex_OnePageCheckout_Block_Onepage extends Mage_Checkout_Block_Onepage
{
    public function __construct()
    {
        $this->getQuote()->getShippingAddress()->setCollectShippingRates(true);
    }
    
    public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? ($this->getQuote()->isVirtual() ? 'payment' : 'shipping' ) : 'login';
    }

    public function canShip()
    {
        return !$this->getQuote()->isVirtual();
    }
}
