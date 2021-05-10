<?php

class Cybercom_Practice1_Adminhtml_Practice1Controller extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('practice1');
        $this->_title('Practice1 Grid');
        $this->_addContent($this->getLayout()->createBlock('practice1/adminhtml_practice1'));
        $this->renderLayout();
    }

    public function _initPractice1()
    {
        $practice1Id = (int) $this->getRequest()->getParam('id');
        $practice1 = Mage::getModel('practice1/practice1')
        ->setStoreId($this->getRequest()->getParam('store', 0))->load($practice1Id);
        
        if (!$practice1Id) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $practice1->setAttributeSetId($setId);
            }
        }
        
        Mage::register('practice1', $practice1);
        Mage::register('current_practice1', $practice1);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $practice1;
    }
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        $practice1Id  = (int) $this->getRequest()->getParam('id');
        $practice1 = $this->_initPractice1();
        
        if ($practice1Id && !$practice1->getId()) {
            $this->_getSession()->addError(Mage::helper('practice1')->__('This Practice1 no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        
        $this->loadLayout();
        $this->_setActiveMenu('practice1');
        
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        
        $block = $this->getLayout()->getBlock('catalog.wysiwyg.js');
        if ($block) {
            $block->setStoreId($practice1->getStoreId());
        }
        
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {

            $practice1Data = $this->getRequest()->getPost('practice1');

            $practice1 = $this->_initPractice1();

            if ($practice1Id = $this->getRequest()->getParam('id')) {

                if (!$practice1->load($practice1Id)->getId()) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            }
           
            $practice1->addData($practice1Data);
            $practice1->save();

            Mage::getSingleton('core/session')->addSuccess("Vendor data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        try {

            $practice1Model = Mage::getModel('practice1/practice1');

            if (!($practice1Id = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$practice1Model->load($practice1Id)) {
                throw new Exception('practice1 does not exist');
            }

            if (!$practice1Model->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The practice1 has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
}
