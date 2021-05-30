<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_ShippingMethod_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $cart = null;

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
    
    public function getShippingMethodTitle()
    {
        return $methods = Mage::getModel('shipping/config')->getActiveCarriers();
    }
}