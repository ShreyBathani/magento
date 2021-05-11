<?php

class Ccc_Vendor_Model_Resource_Product_Group_Name extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct() {
        $this->_init('vendor/vendor_product_group_name', 'entity_id');
    }
}
