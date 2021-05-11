<?php

class Ccc_Vendor_ProductController extends Mage_Core_Controller_Front_Action 
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
            $this->getLayout()->createBlock('vendor/product_grid')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Product'));

        $this->renderLayout();
    }

    public function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product   = Mage::getModel('vendor/product')->load($productId);
        if (!$productId) {
            $setId = Mage::getModel('eav/entity_setup', 'core_setup')->getAttributeSetId('vendor_product', 'Default');
            $product->setAttributeSetId($setId);
        }
            
        $product->setVendorId($this->_getSession()->getVendor()->getId());
        Mage::register('vendor_product', $product);
        //Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product = $this->_initProduct();
        
        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();

    }

    public function saveAction()
    {
        try {

            $productData = $this->getRequest()->getPost();
            if (!$productData) {
                throw new Exception("Please fill the form.");
            }

            $product = $this->_initProduct();

            $product->setVendorProductRequestStatus(Ccc_Vendor_Model_Product_Request::REQUEST_ADD);
            
            if ($productId = $this->getRequest()->getParam('id')) {
                if (!$product->load($productId)->getId()) {
                    throw new Exception("This product no longer exists.");
                }
                if($product->getVendorProductApproved() == Ccc_Vendor_Model_Product_Request::REQUEST_APPROVED){
                    $product->setVendorProductRequestStatus(Ccc_Vendor_Model_Product_Request::REQUEST_EDIT);
                }
            }

            $product->setVendorProductApproved(Ccc_Vendor_Model_Product_Request::REQUEST_PENDING);
            $product->addData($productData);
            
            $product->save();

            $this->_getSession()->addSuccess("Product data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        try {

            $productModel = Mage::getModel('vendor/product');

            if (!($productId = (int) $this->getRequest()->getParam('id'))){
                throw new Exception('Invalid Product.');
            }
            if (!$productModel->load($productId)) {
                throw new Exception('This product no longer exists.');
            }

            if ($productModel->getVendorProductRequestStatus() == Ccc_Vendor_Model_Product_Request::REQUEST_ADD && $productModel->getVendorProductApproved() == Ccc_Vendor_Model_Product_Request::REQUEST_PENDING) {
                $productModel->delete();
                $this->_getSession()->addSuccess($this->__('The product has been deleted.'));
                $this->_redirect('*/*/');
            }

            $productModel->setVendorProductRequestStatus(Ccc_Vendor_Model_Product_Request::REQUEST_DELETED);
            $productModel->setVendorProductApproved(Ccc_Vendor_Model_Product_Request::REQUEST_PENDING);
            
            if (!$productModel->save()) {
                throw new Exception('Error in delete product', 1);
            }

            $this->_getSession()->addSuccess($this->__('The product has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }


}