<?php

class Cybercom_Practice1_Model_Resource_Practice1_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    public function __construct() {
        $this->setEntity('practice1');
        parent::__construct();
    }
}
