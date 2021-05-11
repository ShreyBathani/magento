<?php

class Ccc_Vendor_Model_Product_Request extends Mage_Core_Model_Abstract 
{
    const REQUEST_ADD = "add";
    const REQUEST_EDIT = "edit";
    const REQUEST_DELETED = "deleted";
    const REQUEST_APPROVED = "approved";
    const REQUEST_PENDING = "pending";
    const REQUEST_REJECTED = "rejected";

    protected function _construct()
    {
        $this->_init('vendor/product_request');
    }
}
