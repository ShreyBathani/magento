<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_BillingAddress extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order_create_form_billingAddress';
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_addButton('save', array(
            'label'     => Mage::helper('adminhtml')->__('Save'),
            'onclick'   => 'billingaddressForm.submit();',
            'class'     => 'save',
        ), 1);
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/*/saveBillingAddress', array('id' => $this->getRequest()->getParam('id')));
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Billing Address');
    }
}