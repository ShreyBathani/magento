<?php

class Ccc_order_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "order";
        $this->_controller = "adminhtml_order";
        $this->_addButtonLabel = Mage::helper('sales')->__('Create New Order');
        $this->_headerText = $this->__('Order Grid');
        parent::__construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/order_create/index');
    }
}
