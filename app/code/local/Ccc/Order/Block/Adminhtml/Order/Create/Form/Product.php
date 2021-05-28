<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "order";
        $this->_controller = "adminhtml_order_create_form_product";
        $this->_headerText = $this->__('Select Product to Add');
        $this->_addButtonLabel = Mage::helper('order')->__('Add Select Product to Order');
        parent::__construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/order_create/addItemsToCart', array('_current'=>true));
    }
}