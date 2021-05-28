<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_AccountInfo extends Mage_Adminhtml_Block_Widget_Form_Container
{
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

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Account Information');
    }

    public function getFormUrl()
    {
        return $this->getUrl('*/order_create/saveAccountInfo', ['_current' => true]);
    }
}