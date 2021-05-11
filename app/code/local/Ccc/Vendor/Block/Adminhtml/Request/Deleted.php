<?php

class Ccc_Vendor_Block_Adminhtml_Request_Deleted extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'vendor';    
        $this->_controller = 'adminhtml_request_deleted';
        $this->_headerText = $this->__('Deleted Vendor Product');
        parent::__construct();
    }
}
