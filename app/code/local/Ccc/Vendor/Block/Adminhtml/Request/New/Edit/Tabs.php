<?php

class Ccc_Vendor_Block_Adminhtml_Request_New_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
      parent::__construct();
        $this->setId('edit_form_tabs');
        $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('vendor')->__('Product Information'));
    }

    public function getProduct()
    {
        return Mage::registry('current_vendor_product');
    }

    protected function _prepareLayout()
    {
        $product = $this->getProduct();
        $productAttributes = Mage::getResourceModel('vendor/product_attribute_collection');
      
        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }
        
        if ($setId) {

            $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
                ->setAttributeSetFilter($setId)
                ->setSortOrder()
                ->load();

            $defaultGroupId = 0;

            foreach ($groupCollection as $group) {
                if ($defaultGroupId == 0 or $group->getIsDefault()) {
                    $defaultGroupId = $group->getId();
                }
            }

            foreach ($groupCollection as $group) {
         
                $attributes = array();
                foreach ($productAttributes as $attribute) {
                    if ($product->checkInGroup($attribute->getId(), $setId, $group->getId())) {
                        $attributes[] = $attribute;
                    }
                }
                if (count($attributes)==0) {
                    continue;
                }

                $active = $defaultGroupId == $group->getId();
                $block = $this->getLayout()->createBlock('vendor/adminhtml_request_new_edit_tab_attributes')
                    ->setGroup($group)
                    ->setAttributes($attributes)
                    ->setAddHiddenFields($active)
                    ->toHtml();


                $this->addTab('group_' . $group->getId(), array(
                    'label' => Mage::helper('vendor')->__($group->getAttributeGroupName()),
                    'content' => $block,
                    'active' => $active
                ));
                
            }
        } 
        else {
            $this->addTab('set', array(
                'label'     => Mage::helper('vendor')->__('Settings'),
                'content'   => $this->_translateHtml($this->getLayout()
                    ->createBlock('vendor/adminhtml_request_new_edit_tab_settings')->toHtml()),
                'active'    => true
            ));
        }

        return parent::_prepareLayout();
    }

    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
