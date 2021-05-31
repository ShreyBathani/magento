<?php

class Ccc_Order_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_grid');
        $this->setDefaultSort('order_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    public function _prepareCollection()
    {
        $collection = Mage::getModel('order/order')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('order_id', array(
            'header'=> Mage::helper('order')->__('Order #'),
            'type'  => 'text',
            'index' => 'order_id',
        ));

        $this->addColumn('first_name', array(
            'header'=> Mage::helper('order')->__('First Name'),
            'type'  => 'text',
            'index' => 'first_name',
        ));

        $this->addColumn('last_name', array(
            'header'=> Mage::helper('order')->__('Last Name'),
            'type'  => 'text',
            'index' => 'last_name',
        ));

        $this->addColumn('email', array(
            'header'=> Mage::helper('order')->__('Email'),
            'type'  => 'text',
            'index' => 'email',
        ));

        $this->addColumn('shipping_amount', array(
            'header'=> Mage::helper('order')->__('Shipping Amount'),
            'type'  => 'price',
            'index' => 'shipping_amount',
            'currency_code' => Mage::app()->getStore(0)->getBaseCurrency()->getCode(),
        ));
        
        $this->addColumn('grand_total', array(
            'header'=> Mage::helper('order')->__('Grand Total'),
            'type'  => 'price',
            'index' => 'grand_total',
            'currency_code' => Mage::app()->getStore(0)->getBaseCurrency()->getCode(),
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('order')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '150px',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/order/view', array('order_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
