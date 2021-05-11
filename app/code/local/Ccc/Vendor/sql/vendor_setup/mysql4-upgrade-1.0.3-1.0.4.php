<?php

$installer=$this;

$installer->startSetup();

$query = "ALTER TABLE `vendor_product_entity_varchar` ADD UNIQUE(`attribute_id`,`store_id`,`entity_id`)";

$installer->getConnection()->query($query);
$installer->endSetup();