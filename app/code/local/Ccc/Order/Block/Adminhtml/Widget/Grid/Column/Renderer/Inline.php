<?php

class Ccc_Order_Block_Adminhtml_Widget_Grid_Column_Renderer_Inline extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
    public function render(Varien_Object $row)
    {
        $html = parent::render($row);
        $html = '<input type="number" ';
        $html .= 'name="' . $this->getColumn()->getId() . '" ';
        $html .= 'value="' . $row->getData($this->getColumn()->getIndex()) . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';
        $html .= '<button onclick="updateField(this, '. $row->getId() .'); return false">' . Mage::helper('order')->__('Update') . '</button>';
        return $html;
    }
}