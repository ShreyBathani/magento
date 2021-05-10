<?php

class Cybercom_Practice1_Block_Adminhtml_Practice1_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'practice1';
        $this->_controller = 'adminhtml_practice1';
        $this->_updateButton('save', 'label', Mage::helper('practice1')->__('Save Practice1'));
        $this->_updateButton('delete', 'label', Mage::helper('practice1')->__('Delete Practice1'));
    }

    public function getHeaderText()
    {
        return Mage::helper('practice1')->__('Add/Edit Practice1');
    }
}
