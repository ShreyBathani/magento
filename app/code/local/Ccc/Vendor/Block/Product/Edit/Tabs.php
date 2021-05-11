<?php

class Ccc_Vendor_Block_Product_Edit_Tabs extends Mage_Core_Block_Template
{
    protected $_tabs = [];
    
    public function addTab($key, $label)
    {
        $this->_tabs[$key] = $label;    
    }

    public function getTabs()
    {
        return $this->_tabs;
    }

    protected function _prepareLayout()
    {
        $this->addTab('General', 'General');
        $this->addTab('Price', 'Price');
        $this->getTabGroups();
    }

    public function getTabGroups()
    {
        $vendor = $this->getVendor();
        $vendorGroup = Mage::getResourceModel('vendor/product_group_name_collection')->getGroups($vendor);

        if ($vendorGroup) {
            foreach($vendorGroup as $group){
                $productAttributes = Mage::getResourceModel('vendor/product_attribute_collection')
                ->setAttributeGroupFilter($group->getAttributeGroupId())->load();

                if ($productAttributes->getSize() > 0) {
                    $this->addTab($group->getName(), $group->getName());
                }
            }
        }
    }

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }
}
