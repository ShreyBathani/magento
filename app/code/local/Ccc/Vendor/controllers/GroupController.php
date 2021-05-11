<?php

class Ccc_Vendor_GroupController extends Mage_Core_Controller_Front_Action
{
    protected $_entityTypeId;

    public function preDispatch()
    {
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Product::ENTITY)->getTypeId();
    }

    public function indexAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }
        
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        
        $this->getLayout()->getBlock('head')->setTitle($this->__('Product Attribute'));
        
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }

        $id = $this->getRequest()->getParam('entity_id');
        $model = Mage::getModel('vendor/product_group_name');
        //->setAttributeSetId($this->_entityTypeId);
        
        if ($id) {
            $model->load($id);
            
            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('vendor')->__('This group no longer exists'));
                $this->_redirect('*/*/');
                return;
            }            
        }
        /* echo "<pre>";
        print_r($model);
        die; */
        Mage::register('vendor_product_group', $model);
        
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout(); 
    }
        
    public function saveAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }
        
        if(!$this->getRequest()->isPost()){
            $this->_getSession()->addError(Mage::helper('vendor')->__('Invalid Request.'));
            $this->_redirect('*/*/');
            return;
        }
        
        $session = $this->_getSession();
        $vendor = $session->getVendor();
        
        $data = $this->getRequest()->getPost();
        
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');
            
            $model = Mage::getModel('vendor/product_group_name');
            
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->_getSession()->addError(Mage::helper('vendor')->__('This Group no longer exists.'));
                    $this->_redirect('*/*/');
                    return;
                }
            }

            if($model->isNameExists($data['name'], $vendor->getId())){
                $this->_getSession()->addError(Mage::helper('vendor')->__("Group name '{$data['name']}' already exists."));

                if($id){
                    $this->_redirect('*/*/edit', ['entity_id' => $id]);
                    return;
                }
                $this->_redirect('*/*/edit');
                return;
            }

           
            $model->addData($data);
            $model->setVendorId($vendor->getId());

            $groupModel = Mage::getModel('eav/entity_attribute_group');
            
            if ($model->getAttributeGroupId()) {
                $groupModel->load($model->getAttributeGroupId());
                if (!$groupModel->getId()) {
                    $this->_getSession()->addError(Mage::helper('vendor')->__('This Group no longer exists.'));
                    $this->_redirect('*/*/');
                    return;
                }
            }
            
            $groupModel->setAttributeGroupName($model->getName().'_'.$vendor->getId());
            $groupModel->setAttributeSetId(Mage::getModel('eav/entity_setup', 'core_setup')->getAttributeSetId('vendor_product', 'Default'));
            
            try {
                $groupModel->save();
                $model->setAttributeGroupId($groupModel->getId());
                $model->save();
                $this->_getSession()->addSuccess(Mage::helper('vendor')->__('Group Data Saved.'));
            }
            catch (Exception $e) {
                $this->_getSession()->addError(Mage::helper('vendor')->__('An error occurred while saving this group.'));
            }
        $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }

        $id = $this->getRequest()->getParam('entity_id');
        
        if ($id) {
            $model = Mage::getModel('vendor/product_group_name');
            $model->load($id);
            if (!$model->getId()) {-
                $this->_getSession()->addError(Mage::helper('vendor')->__('This Group no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            $groupModel = Mage::getModel('eav/entity_attribute_group');
            $groupModel->load($model->getAttributeGroupId());

            if (!$groupModel->getId()) {
                $this->_getSession()->addError(Mage::helper('vendor')->__('This Group no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            try {
                $groupModel->delete();
            }
            catch (Exception $e) {
                $this->_getSession()->addError(Mage::helper('vendor')->__('An error occurred while deleting this group.'));
            }

        }
        $this->_redirect('*/*/');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
}
