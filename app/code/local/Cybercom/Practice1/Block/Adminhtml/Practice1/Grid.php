<?php

use function PHPSTORM_META\type;

class Cybercom_Practice1_Block_Adminhtml_Practice1_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        $this->setId('practice1Grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('Asc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('practice1_filter');
        parent::__construct();
    }

    public function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('practice1/practice1')->getCollection()
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname')
        ->addAttributeToSelect('email')
        ->addAttributeToSelect('phoneno')  
        ->addAttributeToSelect('gender');   
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'firstname',
            'practice1/firstname',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'lastname',
            'practice1/lastname',
            'entity_id',
            null,
            'left',
            $adminStore
        );
        $collection->joinAttribute(
            'email',
            'practice1/email',
            'entity_id',
            null,
            'left',
            $adminStore
        );
        $collection->joinAttribute(
            'phoneno',
            'practice1/phoneno',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'id',
            'practice1/entity_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('practice1')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
            ));
        $this->addColumn('firstname',
            array(
                'header' => Mage::helper('practice1')->__('First Name'),
                'width'  => '50px',
                'index'  => 'firstname',
            ));

        $this->addColumn('lastname',
            array(
                'header' => Mage::helper('practice1')->__('Last Name'),
                'width'  => '50px',
                'index'  => 'lastname',
            ));

        $this->addColumn('email',
            array(
                'header' => Mage::helper('practice1')->__('Email'),
                'width'  => '50px',
                'index'  => 'email',
            ));

        $this->addColumn('phoneno',
            array(
                'header' => Mage::helper('practice1')->__('Phone Number'),
                'width'  => '50px',
                'index'  => 'phoneno',
            ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', [
            'store' => $this->getRequest()->getParam('store'), 
            'id' => $row->getId()
        ]);
    }
}
