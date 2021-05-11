<?php

class Ccc_Vendor_AttributeController extends Mage_Core_Controller_Front_Action 
{
    const XML_PATH_ALLOWED_TAGS = 'system/catalog/frontend/allowed_html_tags_list';
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
        
        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('vendor/attribute_grid')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Product Attribute'));
        
        $this->renderLayout();
    }

    protected function _initAction()
    {
        /* $this->_title($this->__('Vendor Product'))
             ->_title($this->__('Attributes'))
             ->_title($this->__('Manage Attributes'));

        if($this->getRequest()->getParam('popup')) {
            $this->loadLayout('popup');
        } else {
            $this->loadLayout()
                ->_setActiveMenu('catlog')
                ->_addBreadcrumb(Mage::helper('vendor')->__('Vendor Product'), Mage::helper('vendor')->__('Vendor Product'))
                ->_addBreadcrumb(
                    Mage::helper('vendor')->__('Manage Vendor Product Attributes'),
                    Mage::helper('vendor')->__('Manage Vendor Product Attributes'))
            ;
        }
        return $this; */
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

        $id = $this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('vendor/resource_eav_attribute')
        ->setEntityTypeId($this->_entityTypeId);
        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This attribute no longer exists'));
                $this->_redirect('*/*/');
                return;
            }

            // entity type check
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This attribute cannot be edited.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // set entered data if was error when we do save
        /* $data = Mage::getSingleton('adminhtml/session')->getAttributeData(true);
        
        if (! empty($data)) {
            $model->addData($data);
        } */
        //die;
    
        Mage::register('entity_attribute', $model);

        $this->_initAction();

        /* $this->_title($id ? $model->getName() : $this->__('New Attribute'));

        $item = $id ? Mage::helper('vendor')->__('Edit Product Attribute')
                    : Mage::helper('vendor')->__('New Product Attribute');

        $this->_addBreadcrumb($item, $item);

        $this->getLayout()->getBlock('attribute_edit_js')
            ->setIsPopup((bool)$this->getRequest()->getParam('popup'));
        */
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout(); 
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }

    public function saveAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }
        
        $data = $this->getRequest()->getPost();
        if ($data) {
            $session = Mage::getSingleton('vendor/session');
            $vendor = $session->getVendor();

            $model = Mage::getModel('vendor/resource_eav_attribute');
            
            $helper = Mage::helper('vendor');

            $id = $this->getRequest()->getParam('attribute_id');

            /* $attribite_code = strtolower($data['frontend_label'][0]);

            $data['attribute_code'] = $attribite_code.'_'.$vendor->getId(); */
            
            

            //validate frontend_input
            if (isset($data['frontend_input'])) {
                /** @var $validatorInputType Mage_Eav_Model_Adminhtml_System_Config_Source_Inputtype_Validator */
                $validatorInputType = Mage::getModel('eav/adminhtml_system_config_source_inputtype_validator');
                if (!$validatorInputType->isValid($data['frontend_input'])) {
                    foreach ($validatorInputType->getMessages() as $message) {
                        $session->addError($message);
                    }
                    $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                    return;
                }
            }

            if ($id) {
                $model->load($id);
                
                if (!$model->getId()) {
                    $session->addError(
                        Mage::helper('vendor')->__('This Attribute no longer exists'));
                    $this->_redirect('*/*/');
                    return;
                }

                // entity type check
                if ($model->getEntityTypeId() != $this->_entityTypeId) {
                    $session->addError(
                        Mage::helper('vendor')->__('This attribute cannot be updated.'));
                    $session->setAttributeData($data);
                    $this->_redirect('*/*/edit');
                    return;
                }

                $data['attribute_code'] = $model->getAttributeCode();
                $data['is_user_defined'] = $model->getIsUserDefined();
                $data['frontend_input'] = $model->getFrontendInput();

                $group_id = $data['group_id'];
                $set_id = Mage::getModel('eav/entity_setup', 'core_setup')->getAttributeSetId('vendor_product', 'Default');
                if ($group_id) {
                    $model->setAttributeSetId($set_id);
                    $model->setAttributeGroupId($group_id);
                    
                }
                else{
                    if($group_id = $model->getGroupId()){
                        $model->removeRelation($group_id);
                    }
                }

            }
            else {
                /**
                * @todo add to helper and specify all relations for properties
                */
                $attribite_code = strtolower($data['frontend_label'][0]);
                $data['attribute_code'] = $attribite_code.'_'.$vendor->getId();

                $validatorAttrCode = new Zend_Validate_Regex(array('pattern' => '/^[a-z][a-z_0-9]{1,254}$/'));
                if (!$validatorAttrCode->isValid($data['attribute_code'])) {
                    $session->addError(
                        Mage::helper('vendor')->__('Attribute code is invalid. Please use only letters (a-z), numbers (0-9) or underscore(_) in this field, first character should be a letter.')
                    );
                    $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                    return;
                }

                $data['source_model'] = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
                $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);

            }
            
            if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
                $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
            }

            $defaultValueField = $model->getDefaultValueByInput($data['frontend_input']);
            
            if ($defaultValueField) {
                $data['default_value'] = $this->getRequest()->getParam($defaultValueField);
            }

            if(!isset($data['apply_to'])) {
                $data['apply_to'] = array();
            }

            $data = $this->_filterPostData($data);
            $model->addData($data);

            if (!$id) {
                $model->setEntityTypeId($this->_entityTypeId);
                $model->setIsUserDefined(1);
            }

            /* if ($this->getRequest()->getParam('set') && $this->getRequest()->getParam('group')) {
                // For creating product attribute on product page we need specify attribute set and group
                $model->setAttributeSetId($this->getRequest()->getParam('set'));
                $model->setAttributeGroupId($this->getRequest()->getParam('group'));
            } */
            
            try {
                /* $attributeModel= Mage::getModel('vendor/product_attribute_name');
                $attributeModel->load($model->getFrontendLabel(), 'name');
                if($attributeModel->checkInAttributeName($model->getFrontendLabel(), $vendor->getId())){

                } */

                $attributeModel= Mage::getModel('vendor/product_attribute_name');
                if($attributeModel->isNameExists($data['frontend_label'][0], $vendor->getId(), $model->getId())){
                    $session->addError(
                        Mage::helper('vendor')->__("The Attribute name '{$data['frontend_label'][0]}' is already exists."));
                    $this->_redirect('*/*/edit', array('attribute_id' => $id));
                    return;       
                }

                $model->save();
                $session->addSuccess(
                        Mage::helper('vendor')->__('The product attribute has been saved.'));
                        
                if ($model->getId()) {
                            
                    $attributeModel= Mage::getModel('vendor/product_attribute_name');
                    $attributeModel->load($model->getId(), 'attribute_id');
                    $attributeModel->setName($data['frontend_label'][0]);
                    $attributeModel->setVendorId($vendor->getId());
                    $attributeModel->setAttributeId($model->getId());
                    $attributeModel->save();  
                }

                Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
                $session->setAttributeData(false);
                
                $this->_redirect('*/*/');
                return;
            } 
            catch (Exception $e) {
                $session->addError($e->getMessage());
                $session->setAttributeData($data);
                $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                return;
            }

            $this->_redirect('*/*/');

        }
    }

    protected function _filterPostData($data)
    {
        if ($data) {
            /** @var $helperCatalog Mage_Catalog_Helper_Data */
            $helperCatalog = Mage::helper('vendor');
            //labels
            foreach ($data['frontend_label'] as & $value) {
                if ($value) {
                    $value = $helperCatalog->stripTags($value);
                }
            }

            if (!empty($data['option']) && !empty($data['option']['value']) && is_array($data['option']['value'])) {
                $allowableTags = isset($data['is_html_allowed_on_front']) && $data['is_html_allowed_on_front']
                    ? sprintf('<%s>', implode('><', $this->_getAllowedTags())) : null;
                foreach ($data['option']['value'] as $key => $values) {
                    foreach ($values as $storeId => $storeLabel) {
                        $data['option']['value'][$key][$storeId]
                            = $helperCatalog->stripTags($storeLabel, $allowableTags);
                    }
                }
            }
        }
        return $data;
    }

    public function deleteAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/account/login');
            return;
        }
        
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            $model = Mage::getModel('vendor/resource_eav_attribute');

            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This attribute cannot be deleted.'));
                $this->_redirect('*/*/');
                return;
            }

            try {
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('The product attribute has been deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('vendor')->__('Unable to find an attribute to delete.'));
        $this->_redirect('*/*/');
    }

    protected function _getAllowedTags()
    {
        return explode(',', Mage::getStoreConfig(self::XML_PATH_ALLOWED_TAGS));
    }
}