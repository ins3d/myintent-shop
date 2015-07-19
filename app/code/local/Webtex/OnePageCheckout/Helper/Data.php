<?php

class Webtex_OnePageCheckout_Helper_Data extends Mage_Core_Helper_Data
{

    const XML_PATH_GUEST_CHECKOUT                  = 'checkout/options/guest_checkout';
    const XML_ONEPAGECHECKOUT_ENABLED              = 'onepagecheckout/default/enabled';
    const XML_ONEPAGECHECKOUT_GUESTALLOW           = 'onepagecheckout/default/guestallow';
    const XML_ONEPAGECHECKOUT_DEFAULTDESIGN        = 'onepagecheckout/default/default_design';
    const XML_ONEPAGECHECKOUT_CARTENABLED          = 'onepagecheckout/default/cartenabled';
    const XML_ONEPAGECHECKOUT_SUMMARY              = 'onepagecheckout/default/summary';
    const XML_ONEPAGECHECKOUT_STEP1_TITLE          = 'onepagecheckout/step_1/steptitle';
    const XML_ONEPAGECHECKOUT_NOLOGIN              = 'onepagecheckout/step_1/nologin';
    const XML_ONEPAGECHECKOUT_LOGINCHECKOUT        = 'onepagecheckout/step_1/logincheckout';
    const XML_ONEPAGECHECKOUT_STEP2_TITLE          = 'onepagecheckout/step_2/steptitle';
    const XML_ONEPAGECHECKOUT_STEP2_ADDRESSBOOK    = 'onepagecheckout/step_2/addressbook';
    const XML_ONEPAGECHECKOUT_STEP2_FIRSTNAME      = 'onepagecheckout/step_2/firstname';
    const XML_ONEPAGECHECKOUT_STEP2_LASTNAME       = 'onepagecheckout/step_2/lastname';
    const XML_ONEPAGECHECKOUT_STEP2_COMPANY        = 'onepagecheckout/step_2/company';
    const XML_ONEPAGECHECKOUT_STEP2_ADDRESS        = 'onepagecheckout/step_2/address';
    const XML_ONEPAGECHECKOUT_STEP2_ADDRESS1       = 'onepagecheckout/step_2/address1';
    const XML_ONEPAGECHECKOUT_STEP2_CITY           = 'onepagecheckout/step_2/city';
    const XML_ONEPAGECHECKOUT_STEP2_STATE          = 'onepagecheckout/step_2/state';
    const XML_ONEPAGECHECKOUT_STEP2_ZIP            = 'onepagecheckout/step_2/zip';
    const XML_ONEPAGECHECKOUT_STEP2_COUNTRY        = 'onepagecheckout/step_2/country';
    const XML_ONEPAGECHECKOUT_STEP2_TELEPHONE      = 'onepagecheckout/step_2/telephone';
    const XML_ONEPAGECHECKOUT_STEP2_FAX            = 'onepagecheckout/step_2/fax';
    const XML_ONEPAGECHECKOUT_STEP2_SHIPPINGMETHOD = 'onepagecheckout/step_2/shippingmethod';
    const XML_ONEPAGECHECKOUT_STEP3_TITLE          = 'onepagecheckout/step_3/steptitle';
    const XML_ONEPAGECHECKOUT_STEP3_PAYMENTMETHOD  = 'onepagecheckout/step_3/paymentmethod';
    const XML_ONEPAGECHECKOUT_STEP3_ADDRESSBOOK    = 'onepagecheckout/step_3/addressbook';
    const XML_ONEPAGECHECKOUT_STEP3_ADDRESS        = 'onepagecheckout/step_3/address';
    const XML_ONEPAGECHECKOUT_STEP3_ADDRESS1       = 'onepagecheckout/step_3/address1';
    const XML_ONEPAGECHECKOUT_STEP3_CITY           = 'onepagecheckout/step_3/city';
    const XML_ONEPAGECHECKOUT_STEP3_STATE          = 'onepagecheckout/step_3/state';
    const XML_ONEPAGECHECKOUT_STEP3_ZIP            = 'onepagecheckout/step_3/zip';
    const XML_ONEPAGECHECKOUT_STEP3_COUNTRY        = 'onepagecheckout/step_3/country';
    const XML_ONEPAGECHECKOUT_STEP3_CONTACTS       = 'onepagecheckout/step_3/contacts';
    const XML_ONEPAGECHECKOUT_STEP3_ACCOUNT        = 'onepagecheckout/step_3/createacc';
    const XML_ONEPAGECHECKOUT_STEP4_TITLE          = 'onepagecheckout/step_4/steptitle';
    const XML_ONEPAGECHECKOUT_STEP4_MESSAGE        = 'onepagecheckout/step_4/message';
    const XML_ONEPAGECHECKOUT_STEP4_CANCELMESSAGE  = 'onepagecheckout/step_4/cancelmessage';
    const XML_ONEPAGECHECKOUT_STEP5_TITLE          = 'onepagecheckout/step_5/steptitle';

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_ENABLED);
    }

    public function isGuestEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_GUEST_CHECKOUT);
    }

    public function isDefaultDesignEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_DEFAULTDESIGN);
    }

    public function isShoppinCart()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_CARTENABLED);
    }

    public function isSummaryEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_SUMMARY);
    }

    public function isWithoutLoginEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_NOLOGIN);
    }

    public function isLoginEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_LOGINCHECKOUT);
    }

    public function getStepTitle($step = 1)
    {
        return Mage::getStoreConfig('onepagecheckout/step_' . $step . '/steptitle');
    }

    public function isAddressBookEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/addressbook');
    }

    public function isFirstnameEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_STEP2_FIRSTNAME);
    }

    public function isLastnameEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_STEP2_LASTNAME);
    }

    public function isCompanyEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_STEP2_COMPANY);
    }

    public function isAddressEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/address');
    }

    public function isAddress1Enabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/address1');
    }

    public function isCityEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/city');
    }

    public function isStateEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/state');
    }

    public function isZipEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/zip');
    }

    public function isCountryEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/country');
    }

    public function isTelephoneEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/telephone');
    }

    public function isFaxEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/fax');
    }

    public function isShippingMethodEnabled($step = 2)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/shippingmethod');
    }

    public function isPaymentMethodEnabled($step = 3)
    {
        return Mage::getStoreConfigFlag('onepagecheckout/step_' . $step . '/paymentmethod');
    }

    public function isContactsEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_STEP3_CONTACTS);
    }

    public function isAccountEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ONEPAGECHECKOUT_STEP3_ACCOUNT);
    }

    public function getMessage()
    {
        return Mage::getStoreConfig(self::XML_ONEPAGECHECKOUT_STEP4_MESSAGE);
    }

    public function getCancelMessage()
    {
        return Mage::getStoreConfig(self::XML_ONEPAGECHECKOUT_STEP4_CANCELMESSAGE);
    }

    public function getSteps()
    {
        $steps = array();
        if(!Mage::helper('customer')->isLoggedIn()) {
            $steps['login'] = array('is_show' => 1,
                                    'allow'   => 1);
            $steps['shipping'] = array('is_show' => 1);
        } else {
            $steps['shipping'] = array('is_show' => 1,
                                    'allow'   => 1);
        }
            $steps['payment'] = array('is_show' => 1);
            $steps['review'] = array('is_show' => 1);
            $steps['confirm'] = array('is_show' => 1);
        
        return $steps;
    }

    public function getActiveStep()
    {
        return Mage::helper('customer')->isLoggedIn() ? 'shipping' : 'login';
    }
    
}
