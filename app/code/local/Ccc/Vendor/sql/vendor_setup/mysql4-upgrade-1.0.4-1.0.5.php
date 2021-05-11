<?php

$installer = $this;

$table = $installer->getConnection()
    ->newTable($installer->getTable('vendor/vendor_product_attribute_name'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
        'identity'  => true,
    ), 'Entity ID')
    
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned' => true,
    ), 'Attribute ID')
    
    ->addColumn('vendor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned' => true,
    ), 'Vendor ID')
    
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ), 'Name');

$installer->getConnection()->createTable($table);

$query = "ALTER TABLE `vendor_product_attribute_name` ADD  FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute`(`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_attribute_name` ADD  FOREIGN KEY (`vendor_id`) REFERENCES `vendor`(`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
$installer->getConnection()->query($query);

$installer->endSetup();