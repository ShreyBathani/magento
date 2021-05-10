<?php

class Cybercom_Practice1_Model_Attribute extends Mage_Eav_Model_Attribute
{
    const MODULE_NAME = 'Cybercom_Practice1';

    protected $_eventObject = 'attribute';

    protected function _construct()
    {
        $this->_init('practice1/attribute');
    }
}
