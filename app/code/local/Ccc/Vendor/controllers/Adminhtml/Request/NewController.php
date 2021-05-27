<?php

class Ccc_Vendor_Adminhtml_Request_NewController extends Mage_Adminhtml_Controller_Action
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
        $this->_title('New Vendor Product');
        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_request_new'));
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
            if($product->getVendorProductApproved() == Ccc_Vendor_Model_Product_Request::REQUEST_APPROVED){
                $this->_getSession()->addSuccess('Product already approved.');
            }
            else{
                $catalogProduct = Mage::getModel('catalog/product');
                $entityTypeId = Mage::getModel('eav/entity')
                    ->settype(Mage_Catalog_Model_Product::ENTITY)
                    ->getTypeId();
                $setId = Mage::getModel('eav/entity_setup', 'core_setup')
                    ->getAttributeSetId(Mage_Catalog_Model_Product::ENTITY, 'Default');
                $catalogProduct->setStoreId(1)
                    ->setWebsiteId([1])
                    ->setAttributeSetId($setId)
                    ->setTypeId('simple')
                    ->setCreatedAt(strtotime('now'))
                    ->setSku($product->getSku())
                    ->setName($product->getName())
                    ->setWeight($product->getWeight())
                    ->setStatus($product->getStatus())
                    ->setTaxClassId(4)
                    ->setVisisbility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                    ->setPrice($product->getPrice())
                    ->setMrspEnabled(1)
                    ->setMsrpDisplayActualPriceType(1)
                    ->setMrsp(1)
                    ->setDescription($product->getDescription())
                    ->setShortDescription($product->getShortDescription())
                    ->setEntityTypeId($entityTypeId);
        
                $catalogProduct->setStockData(array(
                    'is_in_stock' => 1, //Stock Availability
                    'qty' => 50 //qty
                    )
                );
                $catalogProduct->save();
        
                $product->setVendorProductRequestStatus(Ccc_Vendor_Model_Product_Request::REQUEST_ADD)
                    ->setVendorProductApproved(Ccc_Vendor_Model_Product_Request::REQUEST_APPROVED)
                    ->setCatalogProductId($catalogProduct->getId());
                $product->save();
        
                $request = Mage::getModel('vendor/product_request')
                    ->setVendorId($product->getVendorId())
                    ->setVendorProductId($product->getId())
                    ->setCatalogProductId($catalogProduct->getId())
                    ->setRequestType($product->getVendorProductRequestStatus().'/'.$product->getVendorProductApproved());
                $request->save();
        
                $this->_getSession()->addSuccess('New Product successfully saved.');
            }
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
