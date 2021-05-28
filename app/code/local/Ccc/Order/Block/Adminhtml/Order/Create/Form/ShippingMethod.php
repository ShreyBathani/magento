<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_ShippingMethod extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order_create_form_shippingMethod';
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Shippping Method');
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/*/saveShippingMethod', array('id' => $this->getRequest()->getParam('id')));
    }
}