<?php
 
class Devsters_Gift_Block_Adminhtml_Giftcards_Edit extends Mage_Adminhtml_Block_Widget_Grid
{
   protected $_addButtonLabel = 'Add New Gift Card';
 
    public function __construct()
  {
      parent::__construct();
      $this->setId('giftGrid');
      $this->setDefaultSort('gift_card_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('gift/gift')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('gift_card_id', array(
          'header'    => Mage::helper('gift')->__('ID'),
          'align'     =>'right',
          'index'     => 'gift_card_id',
      ));

      $this->addColumn('gift_card_number', array(
          'header'    => Mage::helper('gift')->__('Gift Card Number'),
          'align'     =>'left',
          'index'     => 'gift_card_number',
      ));

      $this->addColumn('gift_card_value', array(
          'header'    => Mage::helper('gift')->__('Gift Card Value'),
          'align'     =>'left',
          'index'     => 'gift_card_value',
      ));

      $this->addColumn('gift_card_balance', array(
          'header'    => Mage::helper('gift')->__('Gift Card Balance'),
          'align'     =>'left',
          'renderer'  => render($this),
          'index'     => 'gift_card_balance',
      ));

      $this->addColumn('order_increment_id', array(
          'header'    => Mage::helper('gift')->__('Order ID'),
          'align'     =>'left',
          'index'     => 'order_increment_id',
      ));
	
	  
      return parent::_prepareColumns();
  }

   public function render(Varien_Object $row)
    {
        $html = Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input::render($row);
 
        $html .= '<button onclick="updateField(this, '. $row->getId() .'); return false">' . Mage::helper('modulename')->__('Update') . '</button>';
 
        return $html;
    }

   
  }