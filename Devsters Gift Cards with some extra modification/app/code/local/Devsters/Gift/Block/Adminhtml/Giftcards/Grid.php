<?php

class Devsters_Gift_Block_Adminhtml_Giftcards_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
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
          'index'     => 'gift_card_balance',
          'renderer' => new Devsters_Gift_Block_Adminhtml_Renderer_Balance(),
      ));

      $this->addColumn('order_increment_id', array(
          'header'    => Mage::helper('gift')->__('Order ID'),
          'align'     =>'left',
          'index'     => 'order_increment_id',
      ));
		$this->addExportType('*/*/exportCsv', Mage::helper('gift')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('gift')->__('XML'));
	  
      return parent::_prepareColumns();
  }

   protected function _prepareMassaction()
    {
        $this->setMassactionIdField('gift_card_id');
        $this->getMassactionBlock()->setFormFieldName('gift');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('gift')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('gift')->__('Are you sure?')
        ));

        return $this;
    }

}