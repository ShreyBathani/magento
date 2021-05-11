<?php

$installer = $this;

$table = $installer->getConnection()
    ->newTable($installer->getTable('vendor/vendor_product_request'))
    ->addColumn('request_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
        'identity'  => true,
    ), 'Entity ID')

    ->addColumn('vendor_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned' => true,
    ), 'Vendor Product ID')
    
    ->addColumn('vendor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned' => true,
    ), 'Vendor ID')

    ->addColumn('catalog_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned' => true,
    ), 'Catalog Product ID')

    ->addColumn('request_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ), 'Request Type')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false, 
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ),'Created At')
    
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false, 
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ),'Updated At');

$installer->getConnection()->createTable($table);

$installer->endSetup();