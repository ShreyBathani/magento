<?php 

class Ccc_Vendor_Model_Product_Attribute_Name extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('vendor/product_attribute_name');   
    }

    public function isNameExists($name, $vendorId, $attribute_id)
    {
        $readConnection = $this->_getResource()->getReadConnection();

        $query = "SELECT * FROM `vendor_product_attribute_name` WHERE `vendor_id` = '{$vendorId}' AND `name` = '{$name}'";
        
        if($attribute_id){
            $query = $query." AND `attribute_id` != {$attribute_id}";
        }
        $result = $readConnection->fetchRow($query);

        if ($result) {
            return true;
        }
        return false;
    }
}

?>