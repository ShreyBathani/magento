<?php

class Ccc_Vendor_Block_Attribute_Edit extends Mage_Core_Block_Template
{
    protected $groups = null;

    public function getPostActionUrl()
    {
        return $this->getUrl('*/attribute/save', ['attribute_id' => $this->getRequest()->getParam('attribute_id')]);
    }

    public function getAttribute()
    {
        return Mage::registry('entity_attribute');
    }

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

    public function getInputOptions()
    {
        return Mage::getModel('vendor/attribute')->getInputOptions();
    }

    public function getGroups()
    {
        if (!$this->groups) {
            $vendor = $this->getVendor();
            $this->groups = Mage::getResourceModel('vendor/product_group_name_collection')->getGroups($vendor);
        }
        return $this->groups;
    }
}
