<?php
class Devsters_Gift_Block_Adminhtml_Giftcards extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
  
    $this->_controller = 'adminhtml_giftcards';
    $this->_blockGroup = 'gift';
    $this->_headerText = Mage::helper('gift')->__('Manage Gift Cards');
    parent::__construct();
   $this->removeButton('add'); 
  }
}