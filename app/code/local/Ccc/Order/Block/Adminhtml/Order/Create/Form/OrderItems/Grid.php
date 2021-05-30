<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_OrderItems_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $cart = null;

    public function __construct() {
        $this->setId('order_create_form_orderItems_grid');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        parent::__construct();
    }
    
    public function setCart(Ccc_Order_Model_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function getCart()
    {
        if (!$this->cart) {
            Mage::throwException(Mage::helper('order')->__('Cart Is not set.'));
        }
        return $this->cart;
    }

    public function _prepareCollection()
    {
        $collection = $this->getCart()->getItems();
        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('order')->__('Name'),
                'index' => 'name',
        ));
        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('order')->__('SKU'),
                'width' => '200px',
                'index' => 'sku',
        ));
        $this->addColumn('base_price',
            array(
                'header'=> Mage::helper('order')->__('Base Price'),
                'index' => 'base_price',
        ));
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('order')->__('Price'),
                'index' => 'price',
        ));
        $this->addColumn('quantity',
            array(
                'header'=> Mage::helper('order')->__('Quantity'),
                'index' => 'quantity',
        ));
        /* $this->addColumn('quantity', array(
            'header' => Mage::helper('order')->__('Quantity'),
            'width' => '300px',
            'renderer' => 'order/adminhtml_widget_grid_column_renderer_inline',
            'index' => 'quantity',
        )); */
        return parent::_prepareColumns();
    }

}