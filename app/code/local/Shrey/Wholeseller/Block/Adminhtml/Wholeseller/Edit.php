<?php

class Shrey_Wholeseller_Block_Adminhtml_Wholeseller_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_wholeseller';
        $this->_blockGroup = 'wholeseller';
        $this->_updateButton('save', 'label', Mage::helper('wholeseller')->__('Save Wholeseller'));
        $this->_updateButton('delete', 'label', Mage::helper('wholeseller')->__('Delete Wholeseller'));
    }

    public function getHeaderText()
    {
        if (Mage::registry('wholeseller') && Mage::registry('wholeseller')->getId()) {
            return Mage::helper('wholeseller')->__('Edit Wholeseller'. Mage::registry('wholeseller')->getFirstName());
        }
        else{
            return Mage::helper('wholeseller')->__('Add Wholeseller');
        }
    }
}
