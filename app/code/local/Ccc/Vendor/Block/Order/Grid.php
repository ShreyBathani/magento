<?php

class Ccc_Vendor_Block_Order_Grid extends Mage_Core_Block_Template
{
    protected $_collection = null;

    public function __construct() 
    {
        $this->setTemplate('vendor/order/grid.phtml');
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
        $collection = Mage::getModel('sales/order_item')->getCollection()
            ->addAttributeToFilter('vendor_id', ['eq' => $this->getVendor()->getId()]);

        $this->setCollection($collection);
        
        return $this;
    }

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }
}
