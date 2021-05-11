<?php
class Ccc_Vendor_Model_Resource_Vendor_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
	public function __construct()
	{
		$this->setEntity('vendor');
		parent::__construct();
		
	}
}