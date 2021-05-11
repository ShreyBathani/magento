<?php

$installer = $this;

$query = "ALTER TABLE sales_flat_order_item ADD COLUMN vendor_id INT (10) UNSIGNED NOT NULL COMMENT 'Vendor Id';";
$installer->getConnection()->query($query);

$installer->endSetup();