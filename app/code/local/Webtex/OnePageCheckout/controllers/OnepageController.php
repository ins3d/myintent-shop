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


class Webtex_OnePageCheckout_OnepageController extends Mage_Checkout_Controller_Action
{
    protected $_sectionUpdateFunctions = array(
        'payment-method'  => '_getPaymentMethodsHtml',
        'shipping-method' => '_getShippingMethodsHtml',
        'review'          => '_getReviewHtml',
    );

    /** @var Mage_Sales_Model_Order */
    protected $_order;
    protected $_onepage;

    /**
     * @return Mage_Checkout_OnepageController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }

        if(!$this->_canShowForUnregisteredUsers()){
            $this->norouteAction();
            $this->setFlag('',self::FLAG_NO_DISPATCH,true);
            return;
        }

        return $this;
    }

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    /**
     * Validate ajax request and redirect on failure
     *
     * @return bool
     */
    protected function _expireAjax()
    {
        if (!$this->getOnepage()->getQuote()->hasItems()
            || $this->getOnepage()->getQuote()->getHasError()
            || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
    }

    /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getShippingMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    /**
     * Get payment method step html
     *
     * @return string
     */
    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getShippingBlockHtml()
    {
        $html  = ' <div class="hor-line-info"></div><div class="col2-set order-info-box"><div class="col-1"><div class="box"><div class="box-title"><h2>' . $this->__('Shipping Address') . '</h2></div><div class="box-content"><address>';
        $html .= $this->getOnepage()->getQuote()->getShippingAddress()->format('html');
        $html .= '</address></div></div></div><div class="col-2"><div class="box"><div class="box-title"><h2>' . $this->__('Shipping Method') . '</h2></div><div class="box-content">';
        if ($this->getOnepage()->getQuote()->getShippingAddress()->getShippingDescription()) {
          $html .= $this->getOnepage()->getQuote()->getShippingAddress()->getShippingDescription();
        } else {
          $html .= '<p>' . Mage::helper('sales')->__('No shipping information available') . '</p>';
        }
        $html .= '</div></div></div></div>';
        
        return $html;
    }

    protected function _getBillingBlockHtml()
    {
        try{
        $this->getOnepage()->getQuote()->save();
        $blockType = $this->getOnepage()->getQuote()->getPayment()->getMethodInstance()->getInfoBlockType();
        if ($this->getLayout()) {
            $block = $this->getLayout()->createBlock($blockType);
        }
        else {
            $className = Mage::getConfig()->getBlockClassName($blockType);
            $block = new $className;
        }
        $block->setInfo($this->getOnepage()->getQuote()->getPayment());

        $html  = ' <div class="hor-line-info"></div><div class="col2-set order-info-box"><div class="col-1"><div class="box"><div class="box-title"><h2>' . $this->__('Billing Address') . '</h2></div><div class="box-content"><address>';
        $html .= $this->getOnepage()->getQuote()->getBillingAddress()->format('html');
        $html .= '</address></div></div></div><div class="col-2"><div class="box"><div class="box-title"><h2>' . $this->__('Payment Method') . '</h2></div><div class="box-content">';
        $html .= $block->toHtml() ;
        $html .= '</div></div></div></div>';
        return $html;
        } catch (Exception $e) {
            return '';
        };
    }

    protected function _getAdditionalHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_additional');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }

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
     * Get order review step html
     *
     * @return string
     */
    protected function _getReviewHtml()
    {
        $layout =  $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('onepagecheckout_onepage_review');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }

    protected function _getConfirmHtml()
    {
        $layout =  $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('onepagecheckout_order_view');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }

    /**
     * Get one page checkout model
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        if(!$this->_onepage) {
           $this->_onepage = Mage::getSingleton('checkout/type_onepage');
        }
        return $this->_onepage;
    }

    /**
     * Checkout page
     */
    public function indexAction()
    {
        if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }

    public function shippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function reviewAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Order success action
     */
    public function successAction()
    {
        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }

        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }


    public function getAdditionalAction()
    {
        $this->getResponse()->setBody($this->_getAdditionalHtml());
    }

    /**
     * Address JSON
     */
    public function getAddressAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $addressId = $this->getRequest()->getParam('address', false);
        if ($addressId) {
            $address = $this->getOnepage()->getAddress($addressId);

            if (Mage::getSingleton('customer/session')->getCustomer()->getId() == $address->getCustomerId()) {
                $this->getResponse()->setHeader('Content-type', 'application/x-json');
                $this->getResponse()->setBody($address->toJson());
            } else {
                $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
            }
        }
    }

    public function getShippingMethodAction()
    {
        if ($this->getRequest()->isPost()) {
            $result['goto_section'] = 'shipping';
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'html' => $this->_getShippingMethodsHtml(),
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function updateDataAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $type     = $this->getRequest()->getPost('section_name', false);
            $shipping = $this->getRequest()->getPost('shipping', array());
            $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $method   = $this->getRequest()->getPost('shipping_method', false);
            $payment  = $this->getRequest()->getPost('payment', false);
            $billing  = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);


            $result = $this->getOnepage()->saveShipping($shipping, $shippingAddressId);

            if(empty($result['error'])) {
                $result = $this->getOnepage()->saveShippingMethod($method);
                if(empty($result['error']) && $payment && $type != 'shipping') {
                    try {
                        $result = $this->getOnepage()->savePayment($payment);
                    } catch (Exception $e){
                        $result['error'] = true;
                        $result['message'] = $e->getMessage();
                    }
                    if(empty($result['error']) && $type != 'shipping') {
                        if(isset($billing['use_shipping_yes'])){
                            $data = $this->getOnepage()->getQuote()->getBillingAddress()->getData();
                            $data['street'] = explode(',', $data['street']);
                            $data = array_merge($data, $shipping);
                            $data['address_type'] = 'billing';
                            $data['customer_address_id'] = null;
                            $customerAddressId = false;
                        } else {
                            $data = $billing;
                        }

                        if (isset($billing['email'])) {
                            $data['email'] = trim($billing['email']);
                        }

                        if(!empty($billing['customer_password']) && ($billing['customer_password'] === $billing['confirm_password'])) {
                            $data['customer_password'] = $billing['customer_password'];
                            $data['confirm_password'] = $billing['confirm_password'];
                            $this->getOnepage()->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_REGISTER);
                        }
                        $result = $this->getOnepage()->saveBilling($data, $customerAddressId);
                    }
                }
            }

            $this->getOnepage()->getQuote()->getShippingAddress()->collectShippingRates();
            $this->getOnepage()->getQuote()->collectTotals()->save();

            if(empty($result['error'])){
               $result['goto_section'] = ($type == 'shipping') ? 'payment' : 'review' ;
            } else {
               $result['goto_section'] = ($type == 'shipping') ? 'shipping' : 'payment' ;
            }

            if($type == 'shipping'){
               $html = $this->_getPaymentMethodsHtml();
               $name = 'payment-method';
            } elseif($type == 'payment') {
               $html = $this->_getReviewHtml();
               $name = 'review';
            } else {
               $html = $this->_getReviewHtml();
               $name = 'review';
            }

            $result['update_section'] = array(
                'shipping'      => $this->_getShippingMethodsHtml(),
                'shipping_info' => $this->_getShippingBlockHtml(),
                'payment'       => $this->_getPaymentMethodsHtml(),
                'payment_info'  => $this->_getBillingBlockHtml(),
                'additional'    => $this->_getAdditionalHtml(),
                'totals'        => $this->_getTotalsHtml(),
                'html'          => $html,
                'name'          => $name,
            );

            if(empty($result['error']) && $type == 'review') {
                $this->_saveOrder();
            } else {
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            }
        }

    }

    public function _saveShippingMethod()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            $data = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $result = $this->getOnepage()->saveShipping($data, $customerAddressId);
            $this->getOnepage()->getQuote()->collectTotals()->save();

            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'shipping' => $this->_getShippingMethodsHtml(),
                'payment' => $this->_getPaymentMethodsHtml(),
                'additional' => $this->_getAdditionalHtml(),
                'totals' => $this->_getTotalsHtml(),
            );
            return $result;
        }
    
    }
    public function updateShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $result = $this->_saveShippingMethod();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
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
            $method = $this->getRequest()->getPost('method');
            $result = $this->getOnepage()->saveCheckoutMethod($method);

            if (!isset($result['error'])) {
                if($this->getOnepage()->getQuote()->isVirtual()){
                    $result['goto_section'] = 'payment';
                } else {
                    $result['goto_section'] = 'shipping';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );
                }
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
    /**
     * Get Order by quoteId
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _getOrder()
    {
        if (is_null($this->_order)) {
            $this->_order = Mage::getModel('sales/order')->load($this->getOnepage()->getQuote()->getId(), 'quote_id');
            if (!$this->_order->getId()) {
                throw new Mage_Payment_Model_Info_Exception(Mage::helper('core')->__("Can not create invoice. Order was not found."));
            }
        }
        return $this->_order;
    }

    /**
     * Create invoice
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function _initInvoice()
    {
        $items = array();
        foreach ($this->_getOrder()->getAllItems() as $item) {
            $items[$item->getId()] = $item->getQtyOrdered();
        }
        /* @var $invoice Mage_Sales_Model_Service_Order */
        $invoice = Mage::getModel('sales/service_order', $this->_getOrder())->prepareInvoice($items);
        $invoice->setEmailSent(true)->register();

        Mage::register('current_invoice', $invoice);
        return $invoice;
    }

    public function _saveOrder()
    {
        if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getOnepage()->saveOrder();
            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
            $this->getOnepage()->getQuote()->save();
            Mage::register('current_order', Mage::getModel('sales/order')->load($this->getOnepage()->getQuote()->getId(), 'quote_id'));
            Mage::getSingleton('customer/session')->setData('order_html', $this->_getConfirmHtml());
            Mage::getSingleton('customer/session')->setData('order_id', Mage::getModel('sales/order')->load($this->getOnepage()->getQuote()->getId(), 'quote_id')->getIncrementId());
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if( !empty($message) ) {
                $result['error_messages'] = $message;
            }
            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                $result['goto_section'] = $gotoSection;
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }

            if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('dob'));
        return $data;
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    protected function _canShowForUnregisteredUsers()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn()
            || $this->getRequest()->getActionName() == 'index'
            || Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote())
            || !Mage::helper('checkout')->isCustomerMustBeLogged();
    }

    public function printAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
