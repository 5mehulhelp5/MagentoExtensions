<?php

class Devsters_Gift_Block_Adminhtml_Renderer_Balance extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{

   public function render(Varien_Object $row)
    {
        $html = parent::render($row);
        $html .= '   <button onclick="updateBalance(this, '. $row->getId() .'); return false;">' . Mage::helper('gift')->__('Update Balance') . '</button>';         
        return $html;
    }
    
}