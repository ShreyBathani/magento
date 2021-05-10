<?php

class Cybercom_Practice1_Model_Resource_Practice1 extends Mage_Eav_Model_Entity_Abstract
{
    const ENTITY = 'practice1';

    public function __construct() {
        $this->setType(self::ENTITY)->setConnection('core_read', 'core_write');
        parent::__construct();
    }
}
