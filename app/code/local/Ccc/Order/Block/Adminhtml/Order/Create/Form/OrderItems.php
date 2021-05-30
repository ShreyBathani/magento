<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_OrderItems extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $cart = null;

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = "order";
        $this->_controller = "adminhtml_order_create_form_orderItems";
        $this->_headerText = $this->__('Items Order');
        //$this->_addButtonLabel = Mage::helper('order')->__('Update');
        //$this->removeButton('add');
    }
    
    public function setCart(Ccc_Order_Model_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function getCart()
    {
        if (!$this->cart) {
            Mage::throwException(Mage::helper('order')->__('Cart Is not set.'));
        }
        return $this->cart;
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/order_create/');
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/order_create/updateCart', ['_current' => true]);
    }
}