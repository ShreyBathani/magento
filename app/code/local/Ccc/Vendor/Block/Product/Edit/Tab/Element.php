<?php

class Ccc_Vendor_Block_Product_Edit_Tab_Element extends Mage_Core_Block_Template
{
    protected $_element;
    protected $_form;
    protected $_formBlock;
    
    public function __construct()
    {
        $this->setTemplate('vendor/product/edit/tab/element.phtml');
    }

    public function makeHtml($attribute)
    {
        
        $element = new Varien_Object(array(
            'name'      => $attribute->getAttributeCode(),
            'value'     => $this->getVendorProduct()->getData()[$attribute->getAttributeCode()],
            'label'     => $attribute->getFrontend()->getLabel(),
            'class'     => $attribute->getFrontend()->getClass(),
            'required'  => $attribute->getIsRequired(),
            'note'      => $attribute->getNote(),
            'type'      => $attribute->getFrontendInput(),
        ));

        $inputType = $attribute->getFrontendInput();

        if ($inputType == 'select') {
            $element->setValues($attribute->getSource()->getAllOptions(true, true));
        } else if ($inputType == 'multiselect') {
            $element->setValues($attribute->getSource()->getAllOptions(false, true));
            $element->setCanBeEmpty(true);
        } else if ($inputType == 'date') {
            $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
            $element->setFormat(Mage::app()->getLocale()->getDateFormatWithLongYear());
        } else if ($inputType == 'datetime') {
            $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
            $element->setTime(true);
            $element->setStyle('width:50%;');
            $element->setFormat(
                Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
            );
        } else if ($inputType == 'multiline') {
            $element->setLineCount($attribute->getMultilineCount());
        }

        $this->setElement($element);

        return $this->toHtml();
    }

    public function getVendorProduct()
    {
        return Mage::registry('vendor_product');
    }

    public function setElement($element)
    {
        $this->_element = $element;
        return $this;
    }

    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    public function setFormBlock($formBlock)
    {
        $this->_formBlock = $formBlock;
        return $this;
    }

    protected function _beforeToHtml()
    {
        $this->assign('form', $this->_form);
        $this->assign('element', $this->_element);
        $this->assign('formBlock', $this->_formBlock);

        return parent::_beforeToHtml();
    }

}
