<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('wholeseller/wholeseller'))
    ->addColumn('wholeseller_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary' => true,
        'identity' => true,
        'nullable' => false,
    ], 'Id')

    ->addColumn('first_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 15, [
        'nullable'=> false,
    ], 'First Name')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null, [
        'default' => 1,
    ], 'Status')

    ->addColumn('Address', Varien_Db_Ddl_Table::TYPE_TEXT, null, [
        'nullable' => true,
    ], 'Address')

    ->addColumn('join_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
        'nullable' => false,
    ], 'Joining Date')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
        'nullable' => true,
    ], 'Created Date')

    ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
        'nullable' => true,
    ], 'Updated Data');

    
$installer->getConnection()->createTable($table);
$installer->endSetup();
