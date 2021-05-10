<?php

class Shrey_Wholeseller_Block_Adminhtml_Wholeseller extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'adminhtml_wholeseller';
        $this->_blockGroup = 'wholeseller';
        $this->_addButtonLabel = "Add Wholeseller";
        $this->_headerText = Mage::helper('wholeseller')->__('Manage Wholeseller');
        parent::__construct();        
    }
}
