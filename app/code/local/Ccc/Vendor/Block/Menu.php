<?php

class Ccc_Vendor_Block_Menu extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('vendor/menu.phtml');
    }

    public function getManageProductUrl()
    {
        return $this->getUrl('*/product/');
    }

    public function getOrderUrl()
    {
        return $this->getUrl('*/order/');
    }

    public function getManageAttributeUrl()
    {
        return $this->getUrl('*/attribute/');
    }
    
    public function getManageGroupUrl()
    {
        return $this->getUrl('*/group/');
    }
}