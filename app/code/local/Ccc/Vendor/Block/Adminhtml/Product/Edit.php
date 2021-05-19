<?php

class Ccc_Vendor_Block_Adminhtml_Product_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'vendor';
        $this->_controller = 'adminhtml_product';
        $this->_updateButton('delete', 'label', Mage::helper('vendor')->__('Delete Product'));
        $this->_removeButton('save');
    }

    public function getHeaderText()
    {
        return Mage::helper('vendor')->__('Add/Edit Product');
    }
}