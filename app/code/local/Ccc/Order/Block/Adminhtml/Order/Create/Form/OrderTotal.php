<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_OrderTotal extends Mage_Adminhtml_Block_Template
{
    public function getCart()
    {
        return Mage::registry('ccc_cart');
    }
}