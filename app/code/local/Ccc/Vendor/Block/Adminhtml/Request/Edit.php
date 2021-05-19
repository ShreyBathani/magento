<?php

class Ccc_Vendor_Block_Adminhtml_Request_Edit extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'vendor';    
        $this->_controller = 'adminhtml_request_edit';
        $this->_headerText = $this->__('Edited Vendor Product');
        parent::__construct();
        $this->_removeButton('add');
    }
}
