<?php

class Ccc_Vendor_Block_Group_Grid extends Mage_Core_Block_Template
{
    protected $_collection = null;

    /* public function __construct() 
    {
        $this->setTemplate('vendor/group/grid.phtml');
    } */

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
        $collection = Mage::getResourceModel('vendor/product_group_name_collection')->getGroups($vendor);

        $this->setCollection($collection);
        
        return $this;
    }

    public function getEditUrl($id)
    {
        return $this->getUrl('*/*/edit', ['entity_id' => $id]);
    }

    public function getDeleteUrl($id)
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $id]);
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
