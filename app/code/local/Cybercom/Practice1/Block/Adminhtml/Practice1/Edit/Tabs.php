<?php

class Cybercom_Practice1_Block_Adminhtml_Practice1_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_attributeTabBlock = 'practice1/adminhtml_practice1_edit_tab_attributes';

    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('practice1')->__('Practice1 Information'));
    }

    protected function _prepareLayout()
    {
        $practice1 = $this->getPractice1();
        $practice1Attributes = Mage::getResourceModel('practice1/practice1_attribute_collection');
      
        if (!($setId = $practice1->getAttributeSetId())) {
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
                foreach ($practice1Attributes as $attribute) {
                    if ($practice1->checkInGroup($attribute->getId(), $setId, $group->getId())) {
                        $attributes[] = $attribute;
                    }
                }
                if (count($attributes)==0) {
                    continue;
                }

                $active = $defaultGroupId == $group->getId();
                $block = $this->getLayout()->createBlock('practice1/adminhtml_practice1_edit_tab_attributes')
                    ->setGroup($group)
                    ->setAttributes($attributes)
                    ->setAddHiddenFields($active)
                    ->toHtml();


                $this->addTab('group_' . $group->getId(), array(
                    'label' => Mage::helper('practice1')->__($group->getAttributeGroupName()),
                    'content' => $block,
                    'active' => $active
                ));
                
            }
        } else {
            $this->addTab('set', array(
                'label'     => Mage::helper('practice1')->__('Settings'),
                'content'   => $this->_translateHtml($this->getLayout()
                    ->createBlock('practice1/adminhtml_practice1_edit_tab_settings')->toHtml()),
                'active'    => true
            ));
        }

        return parent::_prepareLayout();
    }

    /**
     * Retrive product object from object if not from registry
     *
     * @return Cybercom_Practice1_Model_Practice1
     */
    public function getPractice1()
    {
        return Mage::registry('current_practice1');
    }

    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}