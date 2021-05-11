<?php 

class Ccc_Vendor_Model_Product_Group_Name extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('vendor/product_group_name');   
    }

    public function isNameExists($name, $vendorId)
    {
        $readConnection = $this->_getResource()->getReadConnection();

        $query = "SELECT * FROM `vendor_product_group_name` WHERE `vendor_id` = '{$vendorId}' AND `name` = '{$name}'";
        
        $result = $readConnection->fetchRow($query);

        if ($result) {
            return true;
        }
        return false;
    }
}

?>