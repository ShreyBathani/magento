<?php

$installer = $this;

$installer->startSetup();
$installer->addEntityType(Ccc_Vendor_Model_Resource_Product::ENTITY, [
    'entity_model' => 'vendor/product',
    'attribute_model' => 'vendor/attribute',
    'table' => 'vendor/vendor_product',
    'table_prefix' => '',
    'id_field' => '',
    'increment_model' => '',
    'increment_per_store' => '',
    'increment_pad_length' => '',
    'increment_pad_char' => '',
    'additional_attribute_table' => 'vendor/eav_attribute',
    'entity_attribute_collection' => 'vendor/product_attribute_collection',
]);

$installer->createEntityTables('vendor_product_entity');
$installer->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    						->getAttributeSetId('vendor_product', 'Default');

$installer->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'vendor_product'");

$installer->endSetup();