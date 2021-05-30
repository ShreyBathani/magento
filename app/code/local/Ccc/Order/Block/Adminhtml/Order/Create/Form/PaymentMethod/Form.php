<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_PaymentMethod_Form extends Mage_Adminhtml_Block_Widget_Form
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
	
	public function getPayemntMethodTitle()
    {
    	$methods = Mage::getModel('payment/config');
    	$activemethod = $methods->getActiveMethods();
    	unset($activemethod['paypal_billing_agreement']);
    	//unset($activemethod['checkmo']);
    	unset($activemethod['free']);
    	return $activemethod;
    }
}