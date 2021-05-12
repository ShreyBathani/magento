<?php

class Shrey_Wholeseller_Block_Adminhtml_Wholeseller_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('wholesellerGrid');
        $this->setDefaultSort('wholeseller_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('wholeseller/wholeseller')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('wholeseller_id', [
            'header' => Mage::helper('wholeseller')->__('ID'),
            'align' => 'right',
            'index' => 'wholeseller_id',
        ]);

        $this->addColumn('first_name', [
            'header' => Mage::helper('wholeseller')->__('First Name'),
            'index' => 'first_name',
        ]);

        $this->addColumn('status', [
            'header' => Mage::helper('wholeseller')->__('Status'),
            'index' => 'status',
            'options' => [0 => 'Disabled', 1 => 'Enabled'],
        ]);

        $this->addColumn('Address', [
            'header' => Mage::helper('wholeseller')->__('Address'),
            'index' => 'Address',
        ]);

        $this->addColumn('join_date', [
            'header' => Mage::helper('wholeseller')->__('Joining Date'),
            'index' => 'join_date',
            'type' => 'datetime',
        ]);

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}
