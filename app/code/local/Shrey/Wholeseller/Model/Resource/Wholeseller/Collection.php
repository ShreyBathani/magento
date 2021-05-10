<?php

class Shrey_Wholeseller_Model_Resource_Wholeseller_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('wholeseller/wholeseller');
    }
}
