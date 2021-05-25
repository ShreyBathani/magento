<?php

$installer = $this;

$installer->startSetup();
$installer->addAttribute( Ccc_Vendor_Model_Product::ENTITY, 'catalog_product_id', [
        'group' => 'Common',
        'input' => 'text',
        'type' => 'int',
        'label' => 'Catalog Product Id',
        'backend' => '',
        'visible' => 1,
        'source' => 'eav/entity_attribute_source_table',
        'required' => 1,
        'user_defined' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'visible_in_advanced_search' => 0,
        'global' => Ccc_Vendor_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ]
);

$installer->endSetup();