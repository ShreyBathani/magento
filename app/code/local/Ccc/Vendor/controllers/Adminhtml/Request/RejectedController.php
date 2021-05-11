<?php

class Ccc_Vendor_Adminhtml_Request_RejectedController extends Mage_Adminhtml_Controller_Action
{
    public function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product   = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($productId);
            
        Mage::register('current_vendor_product', $product);
        return $product;
    }
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->_title('Rejected Vendor Product');
        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_request_rejected'));
        $this->renderLayout();
    }

    public function viewAction()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product   = $this->_initProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->_title('View Vendor Product');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();  
    }

}
