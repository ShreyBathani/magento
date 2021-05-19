<?php 

class Ccc_Vendor_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('vendor/vendor');
    }
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->_title('Vendor Product Grid');
        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_product'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('vendor/adminhtml_product_grid')->toHtml());
    }

    public function _initProduct()
    {
        $this->_title($this->__('Vendor Product'))
            ->_title($this->__('Manage Vendor Products'));

        $productId = (int) $this->getRequest()->getParam('id');
        $product   = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($productId);
        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }
        }
            
        Mage::register('current_vendor_product', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
       
        $productId = (int) $this->getRequest()->getParam('id');
        $product   = $this->_initProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        //$this->_title($product->getName());

        $this->loadLayout();

        $this->_setActiveMenu('catalog');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    public function saveAction()
    {

        try {

            $productData = $this->getRequest()->getPost('product');

            $product = $this->_initProduct();;
            if ($productId = $this->getRequest()->getParam('id')) {
                
                if (!$product->load($productId)->getId()) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);   
            }
            
            $product->addData($productData);
            $product->save();

            Mage::getSingleton('core/session')->addSuccess("Product data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {

            $productModel = Mage::getModel('vendor/product');

            if (!($productId = (int) $this->getRequest()->getParam('id'))){
                    throw new Exception('Id not found');
            }
            if (!$productModel->load($productId)) {
                throw new Exception('product does not exist');
            }

            if (!$productModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The product has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
}
