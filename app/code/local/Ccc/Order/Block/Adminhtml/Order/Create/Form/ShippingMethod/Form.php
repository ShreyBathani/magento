<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_ShippingMethod_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function getShippingMethodTitle()
    {
        return $methods = Mage::getModel('shipping/config')->getActiveCarriers();
    }

    public function getShippingCode()
	{
		return Mage::registry('ccc_cart')->getShippingMethodCode();
	}
}