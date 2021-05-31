<?php

class Ccc_Order_Block_Adminhtml_Order_View_Form_ShippingAddress extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $order = null;

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order_create_form_shippingAddress';
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_addButton('save', array(
            'label'     => Mage::helper('adminhtml')->__('Save'),
            'onclick'   => 'shippingaddressForm.submit();',
            'class'     => 'save',
        ), 1);
    }
    
    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        if (!$this->order) {
            Mage::throwException(Mage::helper('order')->__('Order Is not set.'));
        }
        return $this->order;
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Shipping Address');
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/*/saveShippingAddress', array('id' => $this->getRequest()->getParam('id')));
    }
}