<?php

class Ccc_Vendor_Adminhtml_Request_DeletedController extends Mage_Adminhtml_Controller_Action
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
        $this->_title('Deleted Vendor Product');
        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_request_deleted'));
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

    public function approveAction()
    {
        try {
            $product = $this->_initProduct();
            $catalogProduct = Mage::getModel('catalog/product')
                ->loadByAttribute('sku', $product->getSku());

            if (!$catalogProduct->getId()) {
                throw new Exception("Invalid product id.");
            }
            
            $request = Mage::getModel('vendor/product_request')
                ->setVendorId($product->getVendorId())
                ->setVendorProductId($product->getId())
                ->setCatalogProductId($catalogProduct->getId())
                ->setRequestType($product->getVendorProductRequestStatus().'/DELETE');
            $request->save();
            
            $catalogProduct->delete();
            $product->delete();
            
            $this->_getSession()->addSuccess('New Product successfully saved.');
        } 
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    public function rejectAction()
    {
        try {
            $productId = (int) $this->getRequest()->getParam('id');
            $product   = $this->_initProduct();

            if ($productId && !$product->getId()) {
                $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            $product->setVendorProductApproved(Ccc_Vendor_Model_Product_Request::REQUEST_REJECTED);
            $product->save();

            $catalogProduct = Mage::getModel('catalog/product')->load('sku', $product->getSku());

            $request = Mage::getModel('vendor/product_request')
                ->setVendorId($product->getVendorId())
                ->setVendorProductId($product->getId())
                ->setCatalogProductId($catalogProduct->getId())
                ->setRequestType($product->getVendorProductRequestStatus().'/'.$product->getVendorProductApproved());
            $request->save();
            
        } 
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
        
    }

}