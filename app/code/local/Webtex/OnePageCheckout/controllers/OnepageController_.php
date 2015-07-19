<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


require_once 'Mage/Checkout/controllers/OnepageController.php';
class Webtex_OnePageCheckout_OnepageController extends Mage_Checkout_OnepageController
{

    protected function _getTotalsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_totals');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }


    /**
     * Checkout page
     */
    public function indexAction()
    {
    	$checkout = $this->getOnepage();
    	if (Mage::helper('customer')->isLoggedIn()) {
    		$checkout->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_LOGIN_IN);
    	} else {
    		$checkout->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_GUEST);
    	}
    	$billingAddress = $this->getRequest()->getParam('billing', null);
    	$checkout->saveBilling($billingAddress, false);

    	if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping'] == 1) {
    		$shippingAddress = $billingAddress;
    	} else {
	    	$shippingAddress = $this->getRequest()->getParam('shipping', null);
    	}
    	$checkout->saveShipping($shippingAddress, false);

    	$shippingMethod = $this->getRequest()->getParam('shipping_method', array());
    	$checkout->saveShippingMethod($shippingMethod);
    	
    	//$this->_saveOrder();
    	return $this;
        
    }

    public function updateShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            //$result = $this->getOnepage()->saveShipping($data, $customerAddressId);

            //if(isset($result['error'])){
            //    unset($result['error']);
            //}
            $result['goto_section'] = 'shipping';
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'shipping' => $this->_getShippingMethodsHtml(),
                'payment' => $this->_getPaymentMethodsHtml(),
                'additional' => $this->_getAdditionalHtml(),
                'totals' => $this->_getTotalsHtml(),
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }


    public function getShippingMethodAction()
    {
        if ($this->getRequest()->isPost()) {
            $result['goto_section'] = 'shipping';
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'html' => $this->_getShippingMethodsHtml()
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Save checkout method
     */
    public function saveMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
                $result['goto_section'] = 'shipping';
                $result['update_section'] = array(
                    'name' => 'shipping-method',
                    'html' => $this->_getShippingMethodsHtml()
                );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

}
