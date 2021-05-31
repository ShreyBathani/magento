<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "order";
        $this->_controller = "adminhtml_order_create_customer";
        $this->_headerText = $this->__('Select Customer');
        $this->_addButtonLabel = Mage::helper('sales')->__('Create New Customer');
        parent::__construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/customer/new');
    }
}