<?php

class Ccc_Vendor_OrderController extends Mage_Core_Controller_Front_Action 
{
    public function indexAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }
        
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('vendor/order_grid')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Order'));

        $this->renderLayout();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
}