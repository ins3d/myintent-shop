<?php

class Trego_QuickView_Helper_Config extends Mage_Core_Helper_Abstract
{
    const XML_PATH_QUICK     = 'quickview/viewsetting/enableview';
    const XML_PATH_DIALOG_WIDTH     = 'quickview/viewsetting/dialog_width';
	
    public function getDialogWidth()
    {
        return Mage::getStoreConfig(self::XML_PATH_DIALOG_WIDTH);
    }	
    public function getQuickview()
    {
        return Mage::getStoreConfig(self::XML_PATH_QUICK);
    }
}