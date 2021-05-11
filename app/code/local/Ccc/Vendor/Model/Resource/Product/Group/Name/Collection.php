<?php

class Ccc_Vendor_Model_Resource_Product_Group_Name_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct() {
        $this->_init('vendor/product_group_name');
    }

    public function getGroups($vendor)
    {
        /* $query = "SELECT *
            FROM eav_attribute_group
                JOIN vendor_product_group_name
                ON eav_attribute_group.attribute_group_id = vendor_product_group_name.attribute_group_id;
            WHERE vendor_product_group_name.vendor_id = {$vendor->getId()}";

        $this->getConnection()->query($query);
        echo "<pre>";
        print_r($this->getConnection()->query($query));
        die; */
        $this->getSelect()
            ->where('main_table.vendor_id=?', $vendor->getId());
		return $this;
    }
}
