<?php

class Ccc_Vendor_Block_Adminhtml_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('vendorProductId');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('vendor_product_filter');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('vendor/product')->getCollection();

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;

        $collection->joinAttribute(
            'id',
            'vendor_product/entity_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'name',
            'vendor_product/name',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'sku',
            'vendor_product/sku',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'catalog_product_id',
            'vendor_product/catalog_product_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'vendor_id',
            'vendor_product/vendor_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('vendor')->__('Id'),
                'width'  => '50px',
                'index'  => 'id',
            ));

        $this->addColumn('name',
        array(
            'header' => Mage::helper('vendor')->__('Name'),
            'width'  => '50px',
            'index'  => 'name',
        ));

        $this->addColumn('sku',
        array(
            'header' => Mage::helper('vendor')->__('SKU'),
            'width'  => '50px',
            'index'  => 'sku',
        ));

        $this->addColumn('catalog_product_id',
        array(
            'header' => Mage::helper('vendor')->__('Catalog Product Id'),
            'width'  => '50px',
            'index'  => 'catalog_product_id',
        ));

        $this->addColumn('vendor_id',
        array(
            'header' => Mage::helper('vendor')->__('Vendor Id'),
            'width'  => '50px',
            'index'  => 'vendor_id',
        ));

        parent::_prepareColumns();
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', [
            '_current' => true,
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getId()
        ]);
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}
