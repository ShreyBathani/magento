<?php

class Cybercom_Practice1_Block_Adminhtml_Practice1_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_practice1_attribute';
        $this->_blockGroup = 'practice1';
        $this->_headerText = Mage::helper('practice1')->__('Manage Attributes');
        $this->_addButtonLabel = Mage::helper('practice1')->__('Add New Attribute');
        parent::__construct();
    }

}
