<?php

class Ccc_Vendor_Block_Form_Login extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('vendor')->__('Vendor Login'));
        return parent::_prepareLayout();
    }

    public function getPostActionUrl()
    {
        return $this->helper('vendor')->getLoginPostUrl();
    }
    
    public function getCreateAccountUrl()
    {
        /* $url = $this->getData('create_account_url');
        if (is_null($url)) {
            $url = $this->helper('vendor')->getRegisterUrl();
        }
        return $url; */
        return $this->helper('vendor')->getRegisterUrl();
    }

    public function getForgotPasswordUrl()
    {
        return $this->helper('vendor')->getForgotPasswordUrl();
    }

    public function getEmail()
    {
        if (-1 === $this->_email) {
            $this->_email = Mage::getSingleton('vendor/session')->getEmail(true);
        }
        return $this->_email;
    }
}
