<?php

class Magestore_Giftvoucher_Block_History extends Mage_Core_Block_Template {

    /**
     * get Helper
     *
     * @return Magestore_Affiliateplus_Helper_Config
     */
    protected function _construct() {
        parent::_construct();
        $customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $collection = Mage::getModel('giftvoucher/credithistory')->getCollection()
                ->addFieldToFilter('main_table.customer_id', $customer_id);
        $collection->setOrder('history_id', 'DESC');
        $this->setCollection($collection);
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'history_pager')
                ->setTemplate('page/html/pager.phtml')
                ->setCollection($this->getCollection());
        $this->setChild('history_pager', $pager);

        $grid = $this->getLayout()->createBlock('giftvoucher/grid', 'history_grid');
        // prepare column

        $grid->addColumn('action', array(
            'header' => $this->__('Action'),
            'index' => 'action',
            'format' => 'medium',
            'align' => 'left',
            'width' => '120px',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/creditaction')->getOptionArray(),
            'searchable' => true,
        ));

        $grid->addColumn('balance_change', array(
            'header' => $this->__('Balance Change'),
            'align' => 'left',
            'type' => 'baseprice',
            'index' => 'balance_change',
            'width' => '120px',
            'render' => 'getBalanceChangeFormat',
            'searchable' => true,
        ));

        $grid->addColumn('giftcard_code', array(
            'header' => $this->__('Gift Card Code'),
            'align' => 'left',
            'width' => '150px',
            'type' => 'text',
            'index' => 'giftcard_code',
            'searchable' => true,
        ));

        $grid->addColumn('order_number', array(
            'header' => $this->__('Order'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'order_number',
            'width' => '80px',
            'render' => 'getOrder',
            'searchable' => true,
        ));

        $grid->addColumn('currency_balance', array(
            'header' => $this->__('Current Balance'),
            'align' => 'left',
            'width' => '150px',
            'type' => 'baseprice',
            'index' => 'currency_balance',
            'render' => 'getBalanceFormat',
        ));

        $grid->addColumn('created_date', array(
            'header' => $this->__('Changed Time'),
            'index' => 'created_date',
            'type' => 'date',
            'format' => 'medium',
            'align' => 'left',
            'width' => '120px',
            'searchable' => true,
        ));


        $this->setChild('history_grid', $grid);
        return $this;
    }

    public function getBalanceFormat($row) {
        $currency = Mage::getModel('directory/currency')->load($row->getCurrency());
        return $currency->format($row->getCurrencyBalance());
    }

    public function getOrder($row) {
        if ($row->getOrderId()) {
            $render = '<a href="' . $this->getUrl('sales/order/view', array('order_id' => $row->getOrderId())) . '">' . $row->getOrderNumber() . '</a>';
            return $render;
        }
        return 'N/A';
    }

    public function getBalanceChangeFormat($row) {
        $currency = Mage::getModel('directory/currency')->load($row->getCurrency());
        return $currency->format($row->getBalanceChange());
    }

    public function getPagerHtml() {
        return $this->getChildHtml('history_pager');
    }

    public function getGridHtml() {
        return $this->getChildHtml('history_grid');
    }

    protected function _toHtml() {
        $this->getChild('history_grid')->setCollection($this->getCollection());
        return parent::_toHtml();
    }

    public function getBalanceAccount() {
        $store = Mage::app()->getStore();
        $creadit = Mage::getModel('giftvoucher/credit')->getCreditAccountLogin();
        $currency = Mage::app()->getStore()->getCurrentCurrency();

        return $currency->format($store->convertPrice($creadit->getBalance()));
    }

}
