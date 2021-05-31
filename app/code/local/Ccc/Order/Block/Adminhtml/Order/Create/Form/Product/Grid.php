<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('order_create_form_grid');
        $this->setDefaultDir('DESC');
        $this->setDefaultSort('created_at');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    public function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku');
        $collection->joinAttribute(
            'price',
            'catalog_product/price',
            'entity_id',
            null,
            'left',
            Mage::app()->getStore(0)->getId()
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('order')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('order')->__('Name'),
                'index' => 'name',
        ));
        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('order')->__('SKU'),
                'width' => '100px',
                'index' => 'sku',
        ));
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('order')->__('Price'),
                'width' => '100px',
                'type'  => 'price',
                'currency_code' => Mage::app()->getStore(0)->getBaseCurrency()->getCode(),
                'index' => 'price',
        ));
        
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('Add To Cart', array(
            'label'=> Mage::helper('order')->__('Add To Cart'),
            'url'  => $this->getUrl('*/*/addItemsToCart', array('_current'=>true)),
            'selected' => true,
        ));
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/productGrid', array('_current'=>true));
    }
}