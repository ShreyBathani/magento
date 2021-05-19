<?php

$installer = $this;
$installer->startSetup();

$installer->updateAttribute(Ccc_Vendor_Model_Product::ENTITY,'price','frontend_input', 'price');
$installer->updateAttribute(Ccc_Vendor_Model_Product::ENTITY,'price','backend_type', 'decimal');
$installer->updateAttribute(Ccc_Vendor_Model_Product::ENTITY,'weight','frontend_input', 'weight');
$installer->updateAttribute(Ccc_Vendor_Model_Product::ENTITY,'weight','backend_type', 'decimal');   

$query = "ALTER TABLE `vendor_product_entity_decimal` ADD UNIQUE(`attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);


$query = "ALTER TABLE `vendor_product_entity_int` ADD UNIQUE(`attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);


$query = "ALTER TABLE `vendor_product_entity_text` ADD UNIQUE(`attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);


$query = "ALTER TABLE `vendor_product_entity_datetime` ADD UNIQUE(`attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);


$query = "ALTER TABLE `vendor_product_entity_char` ADD UNIQUE(`attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$installer->endSetup();
