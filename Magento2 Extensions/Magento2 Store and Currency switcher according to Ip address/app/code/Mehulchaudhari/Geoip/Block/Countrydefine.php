<?php

namespace Mehulchaudhari\Geoip\Block;

class Countrydefine extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected $magentoOptions;
    
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    
    protected $_template = 'Mehulchaudhari_Geoip::form/field/array.phtml';
    
    protected $_systemStore;
    
    protected $_geoipHelper;
    
    protected $_objectManager = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Framework\View\Design\Theme\LabelFactory $labelFactory
     * @param array $data
     */
     public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Mehulchaudhari\Geoip\Helper\Data $geoipHelper,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->_objectManager = $objectManager;
        $this->_geoipHelper = $geoipHelper;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $data);
    }
    
    protected function _construct()
    {
    
        $this->addColumn('countryCode', ['label' => __('Country Code'), 'size' => 28]);
        $this->addColumn('currencyCode', ['label' => __('Currency Code'), 'size' => 28]);
        $this->addColumn('store', ['label' => __('Store'), 'size' => 28]);
        
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Line');
        parent::_construct();
    }
    
   public function renderCellTemplate($columnName)
    {
         $options = [];
         if ($columnName == 'currencyCode' && isset($this->_columns[$columnName])) {
              $optionsCurency = $this->_objectManager->get('Magento\Directory\Model\Currency')->getConfigAllowCurrencies();
              foreach($optionsCurency as $code => $currency){
                $options[] = ['value'=>$currency ,'label'=>$currency];
               }
          }else if($columnName == 'store' && isset($this->_columns[$columnName])) {
               $options = $this->_systemStore->getStoreValuesForForm(false, false);
          }else if($columnName == 'countryCode' && isset($this->_columns[$columnName])){
              $optionsCurency = $this->_geoipHelper->getCountryList();
              foreach($optionsCurency as $code => $country){
                $options[] = ['value'=>$code ,'label'=>$country];
              }
              /*$options = $this->_objectManager->get('Magento\Directory\Model\Config\Source\Country')->toOptionArray();
               array_shift($options);*/
          }
          if(count($options)>0)
          {
                    $element = $this->_elementFactory->create('select');
		    $element->setForm(
		        $this->getForm()
		    )->setName(
		        $this->_getCellInputElementName($columnName)
		    )->setHtmlId(
		        $this->_getCellInputElementId('<%- _id %>', $columnName)
		    )->setValues(
		        $options
		    );
		    return str_replace("\n", '', $element->getElementHtml());
          }
        return parent::renderCellTemplate($columnName);
    }
}
