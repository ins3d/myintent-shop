<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Myintent_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
	public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
					'checkout_controller_onepage_save_shipping_method', 
					 array(
						  'request' => $this->getRequest(), 
						  'quote'   => $this->getOnepage()->getQuote()));
                $this->getOnepage()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                $result['goto_section'] = 'billing';
//                $result['goto_section'] = 'payment';
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
    //            $postData = $this->getRequest()->getPost('billing', array());
    //            $data = $this->_filterPostData($postData);
            $billing_data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
 
            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $billing_result = $this->getOnepage()->saveBilling($billing_data, $customerAddressId);
 
            $payment_data = $this->getRequest()->getPost('payment', array());
            $payment_result = $this->getOnepage()->savePayment($payment_data);
 
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
 
            if (empty($billing_result['error']) && empty($payment_data['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');
				
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );				
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
 
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}