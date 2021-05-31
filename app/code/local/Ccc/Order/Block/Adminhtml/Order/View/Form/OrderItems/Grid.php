<?php

class Ccc_Order_Block_Adminhtml_Order_View_Form_OrderItems_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $order = null;

    public function __construct() {
        $this->setId('order_create_form_orderItems_grid');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        parent::__construct();
    }
    
    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        if (!$this->order) {
            Mage::throwException(Mage::helper('order')->__('Order Is not set.'));
        }
        return $this->order;
    }

    public function _prepareCollection()
    {
        $collection = $this->getOrder()->getItems();
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