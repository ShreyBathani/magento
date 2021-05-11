<?php

class Ccc_Vendor_Block_Attribute_Grid extends Mage_Core_Block_Template
{
    protected $_collection = null;
    
    public function __construct()
    {
        $this->setTemplate('vendor/attribute/grid.phtml');
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
        $vendor = $this->getVendor();
        
        $collection = Mage::getResourceModel('vendor/product_attribute_collection')->getVendorProductAttributes($vendor);

        $this->setCollection($collection);
        
        return $this;
    }

    public function getEditUrl($id)
    {
        return $this->getUrl('*/*/edit', ['attribute_id' => $id]);
    }

    public function getDeleteUrl($id)
    {
        return $this->getUrl('*/*/delete', ['attribute_id' => $id]);
    }

    public function getAddNewUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

}
