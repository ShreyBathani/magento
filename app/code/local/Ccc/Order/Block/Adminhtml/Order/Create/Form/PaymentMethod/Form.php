<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_PaymentMethod_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function getPayemntMethodTitle()
    {
    	$methods = Mage::getModel('payment/config');
    	$activemethod = $methods->getActiveMethods();
    	unset($activemethod['paypal_billing_agreement']);
    	//unset($activemethod['checkmo']);
    	unset($activemethod['free']);
    	return $activemethod;
    }

	public function getPaymentCode()
	{
		return Mage::registry('ccc_cart')->getPaymentMethodCode();
	}
}