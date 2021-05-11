<?php

class Ccc_Vendor_Block_Product_Grid extends Mage_Core_Block_Template
{
    protected $_collection = null;
    
    public function __construct()
    {
        $this->setTemplate('vendor/product/grid.phtml');
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }

    public function getCollection()
    {
        $this->_prepareCollection();
        return $this->_collection;
    }
    
    public function _prepareCollection()
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
            'price',
            'vendor_product/price',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'weight',
            'vendor_product/weight',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->addAttributeToFilter('vendor_id', ['eq' => $this->getVendor()->getId()])
            ->addAttributeToFilter('vendor_product_request_status', ['neq' => Ccc_Vendor_Model_Product_Request::REQUEST_DELETED]);
        $this->setCollection($collection);
        
        return $this;
    }
    
    public function getAddNewUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getEditUrl($id)
    {
        return $this->getUrl('*/*/edit', ['id' => $id]);
    }

    public function getDeleteUrl($id)
    {
        return $this->getUrl('*/*/delete', ['id' => $id]);
    }
    
    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

}
