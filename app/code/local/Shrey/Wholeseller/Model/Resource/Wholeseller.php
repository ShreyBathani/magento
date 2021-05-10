<?php

class Shrey_Wholeseller_Model_Resource_Wholeseller extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('wholeseller/wholeseller', 'wholeseller_id');
    }
}
