<?php

class Ccc_Vendor_Block_Product_Edit extends Mage_Core_Block_Template
{
    protected $groupAttribute = [];

    public function getGroupAttributes()
    { 
        if (!$this->groupAttribute) {
            $this->prepareGroupAttribute();
        }
        return $this->groupAttribute;
    }

    public function getAttributeHtml($attribute)
    {
        return $this->getLayout()->createBlock('vendor/product_edit_tab_element')->makeHtml($attribute);
    }

    public function prepareGroupAttribute()
    {
        // key = name
        //value = collection+
        $set_id = Mage::getModel('eav/entity_setup', 'core_setup')->getAttributeSetId('vendor_product', 'Default');
        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
        ->setAttributeSetFilter($set_id)
        ->setSortOrder()
        ->load();
        
        foreach($groupCollection as $group){
            if ($group->getAttributeGroupName() == 'General' || $group->getAttributeGroupName() == 'Price') {
                $productAttributes = Mage::getResourceModel('vendor/product_attribute_collection')
                ->setAttributeGroupFilter($group->getId())->load();

                if ($productAttributes->getSize()) {
                    $this->groupAttribute[$group->getAttributeGroupName()] = $productAttributes;
                }
            }
        }

        $vendor = $this->getVendor();
        $vendorGroup = Mage::getResourceModel('vendor/product_group_name_collection')->getGroups($vendor);

        if ($vendorGroup) {
            foreach($vendorGroup as $group){
                $productAttributes = Mage::getResourceModel('vendor/product_attribute_collection')
                ->setAttributeGroupFilter($group->getAttributeGroupId())->load();

                if ($productAttributes->getSize() > 0) {
                    $this->groupAttribute[$group->getName()] = $productAttributes;
                }
            }
        }

    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]);
    }

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }
}
