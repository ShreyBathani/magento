<?php
class Ccc_Vendor_Block_Group_Edit extends Mage_Core_Block_Template
{
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
    }

    public function getGroup()
    {
        return Mage::registry('vendor_product_group');
    }
}

