<?php

class Shrey_Wholeseller_Adminhtml_WholesellerController extends Mage_Adminhtml_Controller_Action
{
    function indexAction()
    {
        $this->loadLayout();    
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            //$wholeseller = Mage::getModel('wholeseller/wholeseller')->load($id);
            $wholeseller = Mage::getModel('wholeseller/wholeseller');
            /* if ($wholeseller->getId() || $id == 0) {
                Mage::register('wholeseller', $wholeseller);
            } */
            if ($id) {
                $wholeseller->load($id);
                if (!$wholeseller->getId()) {
                    Mage::throwException(Mage::helper('wholeseller')->__('Invalid Id.'));
                }
            }
            Mage::register('wholeseller', $wholeseller);
            $this->loadLayout();
            $this->renderLayout();
        }
        catch (Exception $e) {
            $this->_getSession()->addError(Mage::helper('wholeseller')->__($e->getMessage()));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        try {
            if (!$this->getRequest()->isPost()) {
                Mage::throwException(Mage::helper('wholeseller')->__('Invalid Request.'));
            }
            $postData = $this->getRequest()->getPost();
            
            $id = $this->getRequest()->getParam('id');
            $wholeseller = Mage::getModel('wholeseller/wholeseller');
            if ($id) {
                $wholeseller->load($id);
                if (!$wholeseller->getId()) {
                    Mage::throwException(Mage::helper('wholeseller')->__('Invalid Id.'));
                }
                $wholeseller->setId($id);
            }
            if(!$id){
                $wholeseller->setJoinDate(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
            }
            $wholeseller->addData($postData);
            $wholeseller->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wholeseller')->__('Wholeseller successfully saved.'));
        } 
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('wholeseller')->__($e->getMessage()));
        }
        $this->_redirect('*/*/');
    }
    
    public function deleteAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');    
            if (!$id) {
                Mage::throwException(Mage::helper('wholeseller')->__('Invalid Id.'));
            }
            $wholeseller = Mage::getModel('wholeseller/wholeseller')->load($id);
            if (!$wholeseller->getId()) {
                Mage::throwException(Mage::helper('wholeseller')->__('Wholeseller not found.'));
            }
            $wholeseller->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wholeseller')->__('Wholeseller successfully deleted.'));
        } 
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('wholeseller')->__($e->getMessage()));
        }
        $this->_redirect('*/*/');
    }
}
