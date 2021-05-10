<?php

$installer = $this;

$installer->startSetup();

$installer->addEntityType(Cybercom_Practice1_Model_Resource_Practice1::ENTITY, [
    'entity_model' => 'practice1/practice1',
    'attribute_model' => 'practice1/attribute',
    'table' => 'practice1/practice1',
    'table_prefix' => '',
    'id_field' => '',
    'increment_model' => '',
    'increment_per_store' => '',
    'increment_pad_length' => '',
    'increment_pad_char' => '',
    'additional_attribute_table' => 'practice1/practice1_eav_attribute',
    'entity_attribute_collection' => 'practice1/practice1_attribute_collection',
]);

$installer->createEntityTables('practice1');
$installer->installEntities();


$installer->endSetup();

