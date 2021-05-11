<?php

class Ccc_Vendor_Block_Account_Dashboard extends Mage_Core_Block_Template
{
    protected $_subscription = null;

    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

    public function getAccountUrl()
    {
        return Mage::getUrl('vendor/account/edit', array('_secure'=>true));
    }

    /* public function getOrdersUrl()
    {
        return Mage::getUrl('vendor/order/index', array('_secure'=>true));
    } */

    public function getTagsUrl()
    {

    }

    // public function getSubscriptionObject()
    // {
    //     if(is_null($this->_subscription)) {
    //         $this->_subscription = Mage::getModel('newsletter/subscriber')->loadByVendor($this->getVendor());
    //     }

    //     return $this->_subscription;
    // }

    // public function getManageNewsletterUrl()
    // {
    //     return $this->getUrl('*/newsletter/manage');
    // }

    /* public function getSubscriptionText()
    {
        if($this->getSubscriptionObject()->isSubscribed()) {
            return Mage::helper('vendor')->__('You are currently subscribed to our newsletter.');
        }

        return Mage::helper('vendor')->__('You are currently not subscribed to our newsletter.');
    } */

    public function getBackUrl()
    {
        // the RefererUrl must be set in appropriate controller
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('vendor/account/');
    }
}
