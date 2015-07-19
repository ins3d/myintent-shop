<?php
class Webtex_OnePageCheckout_Block_Totals extends Mage_Checkout_Block_Cart_Totals
{
    public function getTotals()
    {
        $totals = $this->getQuote()->getTotals();
        return $totals;
    }

}
