<?php

class Trego_QuickView_Helper_Quickview extends Mage_Core_Helper_Abstract
{
    const XML_PATH_QUICK     = 'quickview/viewsetting/enableview';
    const XML_PATH_DIALOG_WIDTH     = 'quickview/viewsetting/dialog_width';
    const XML_PATH_TITLE     = 'quickview/viewsetting/title';

	public function getDialogWidth()
    {	if(Mage::getStoreConfig(self::XML_PATH_DIALOG_WIDTH)==""){
			return 890;
		}else{
			return Mage::getStoreConfig(self::XML_PATH_DIALOG_WIDTH);
		}
    }	
	public function getQuickview()
    {
        return Mage::getStoreConfig(self::XML_PATH_QUICK);
    }
    public function getTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_TITLE);
    }
}