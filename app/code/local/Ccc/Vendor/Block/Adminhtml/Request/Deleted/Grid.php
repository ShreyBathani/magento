<?php

class Ccc_Vendor_Block_Adminhtml_Request_Deleted_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('vendorProductId');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('deleteed_vendor_product_filter');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('vendor/product')->getCollection();

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;

        $collection->joinAttribute(
            'id',
            'vendor_product/entity_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'vendor_id',
            'vendor_product/vendor_id',
            'entity_id',
            null,
            'left',
            $adminStore
        );
        
        $collection->joinAttribute(
            'name',
            'vendor_product/name',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'sku',
            'vendor_product/sku',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->joinAttribute(
            'price',
            'vendor_product/price',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        $collection->addAttributeToFilter('vendor_product_request_status', ['=' => Ccc_Vendor_Model_Product_Request::REQUEST_DELETED])
                    ->addAttributeToFilter('vendor_product_approved', ['=' => Ccc_Vendor_Model_Product_Request::REQUEST_PENDING]);
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('vendor')->__('Id'),
            'index'  => 'id',
        ));

        $this->addColumn('vendor_id', array(
            'header' => Mage::helper('vendor')->__('Vendor Id'),
            'index'  => 'vendor_id',
            ));
            
        $this->addColumn('name', array(
            'header' => Mage::helper('vendor')->__('Name'),
            'index'  => 'name',
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('vendor')->__('SKU'),
            'index'  => 'sku',
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('vendor')->__('Price'),
            'index'  => 'price',
        ));

        $this->addColumn('approve',
            array(
                'header'    => Mage::helper('vendor')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('vendor')->__('Approve'),
                        'url'     => array(
                            'base'=>'*/*/approve',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));

        $this->addColumn('reject',
            array(
                'header'    => Mage::helper('vendor')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('vendor')->__('Reject'),
                        'url'     => array(
                            'base'=>'*/*/reject',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));

        // $this->addColumn('view',
        //     array(
        //         'header'    => Mage::helper('vendor')->__('Action'),
        //         'width'     => '50px',
        //         'type'      => 'action',
        //         'getter'     => 'getId',
        //         'actions'   => array(
        //             array(
        //                 'caption' => Mage::helper('vendor')->__('View'),
        //                 'url'     => array(
        //                     'base'=>'*/*/view',
        //                     'params'=>array('store'=>$this->getRequest()->getParam('store'))
        //                 ),
        //                 'field'   => 'id'
        //             )
        //         ),
        //         'filter'    => false,
        //         'sortable'  => false,
        //         'index'     => 'stores',
        // ));

        parent::_prepareColumns();
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', [
            '_current' => true,
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getId()
        ]);
    }

}