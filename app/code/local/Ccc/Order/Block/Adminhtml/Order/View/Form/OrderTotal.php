<?php

class Ccc_Order_Block_Adminhtml_Order_View_Form_OrderTotal extends Mage_Adminhtml_Block_Template
{
    protected $order = null;

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
}