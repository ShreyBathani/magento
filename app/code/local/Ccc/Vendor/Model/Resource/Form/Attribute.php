<?php

class Ccc_Vendor_Model_Resource_Form_Attribute extends Mage_Eav_Model_Resource_Form_Attribute
{
    public function _construct() {
        $this->_init('vendor/form_attribute', 'attribute_id');
    }
}
