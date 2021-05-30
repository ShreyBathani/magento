<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_AccountInfo_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $cart = null;
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'account_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        //$fieldset = $form->addFieldset('account_form', array('legend'=>Mage::helper('order')->__('')));

        $form->addField('group', 'select', array(
        'label' => Mage::helper('order')->__('Group'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'group',
        ));

        $form->addField('email', 'text', array(
        'label' => Mage::helper('order')->__('Email'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'email',
        ));
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

}