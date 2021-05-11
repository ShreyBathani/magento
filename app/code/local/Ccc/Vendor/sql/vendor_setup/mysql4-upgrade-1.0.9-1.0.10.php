<?php

$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute(Ccc_Vendor_Model_Resource_Vendor::ENTITY, 'password_hash', array(
    'group'                      => 'General',
    'input'                      => 'text',
    'type'                       => 'varchar',
    'label'                      => 'password_hash',
    'backend'                    => '',
    'visible'                    => 1,
    'required'                   => 0,
    'user_defined'               => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 1,
    'visible_on_front'           => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front'   => 1,
    'global'                     => Ccc_Vendor_Model_Resource_Eav_Attribute::SCOPE_STORE,

));

/* $this->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Product::ENTITY)->getTypeId(), 'firstname'); */

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'firstname');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_create', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'lastname');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_create', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'email');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_create', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'phoneNo');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_create', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'password_hash');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_create', '{$attribute_id}')";
$installer->getConnection()->query($query);



$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'firstname');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_edit', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'lastname');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_edit', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'email');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_edit', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'phoneNo');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_edit', '{$attribute_id}')";
$installer->getConnection()->query($query);

$attribute_id = $installer->getAttributeId(Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Vendor::ENTITY)->getTypeId(), 'password_hash');
$query = $sql = "INSERT INTO vendor_form_attribute (form_code, attribute_id) 
    VALUES ('vendor_account_edit', '{$attribute_id}')";
$installer->getConnection()->query($query);
$installer->endSetup();