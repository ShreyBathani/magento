<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_AccountInfo extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $cart = null;

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order_create_form_accountInfo';
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('save');
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

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Account Information');
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/order_create/saveAccountInfo', ['_current' => true]);
    }
}