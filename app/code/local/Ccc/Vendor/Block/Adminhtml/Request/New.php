<?php

class Ccc_Vendor_Block_Adminhtml_Request_New extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'vendor';    
        $this->_controller = 'adminhtml_request_new';
        $this->_headerText = $this->__('New Vendor Product');
        parent::__construct();
    }
}
