<?php

class Ccc_Vendor_Model_Resource_Product_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection 
{
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where('main_table.entity_type_id=?', Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Resource_Product::ENTITY)->getTypeId())
            ->join(
                array('additional_table' => $this->getTable('vendor/eav_attribute')),
                'additional_table.attribute_id = main_table.attribute_id'
            );
        return $this;

    }

    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    public function getVendorProductAttributes($vendor)
	{
		$this->getSelect()
			->join(
                array('vendor_attribute_name_table' => $this->getTable('vendor/vendor_product_attribute_name')), 
                'additional_table.attribute_id = vendor_attribute_name_table.attribute_id'
            )
			->where('vendor_attribute_name_table.vendor_id=?', $vendor->getId());

		return $this;
	}
}