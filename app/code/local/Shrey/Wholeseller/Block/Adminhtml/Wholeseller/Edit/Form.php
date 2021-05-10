<?php

class Shrey_Wholeseller_Block_Adminhtml_Wholeseller_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form([
            'id' => 'edit_form', 
            'action' => $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]),
            'method' =>'post',
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('wholeseller_form',['legend' => Mage::helper('wholeseller')->__('Wholeseller Information')]);
        $fieldset->addField('first_name', 'text', array(
            'label' => Mage::helper('wholeseller')->__('First Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'first_name',
        ));
            
        $fieldset->addField('status', 'select', [
            'label' => Mage::helper('wholeseller')->__('Status'),
            'class' => 'required-entry',
            'values' => [0 => 'Disabled', 1 => 'Enabled'],
            'name' => 'status',
            ]);
            
        $fieldset->addField('Address', 'text', [
            'label' => Mage::helper('wholeseller')->__('Address'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'Address',
        ]);

        if (Mage::getSingleton('adminhtml/session')->getWholesellerData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getWholesellerData());
            Mage::getSingleton('adminhtml/session')->setWholesellerData(null);
        }
        elseif(Mage::registry('wholeseller')){
            $form->setValues(Mage::registry('wholeseller')->getData());
        }
        return parent::_prepareForm();
    }
}
