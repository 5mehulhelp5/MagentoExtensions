<?php
namespace Mehulchaudhari\Daisyconfeed\Block;
class Defaultattributes extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected $magentoOptions;
    
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    
    protected $_eavConfig;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Framework\View\Design\Theme\LabelFactory $labelFactory
     * @param array $data
     */
     public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->_eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }
    
    protected function _construct()
    {
    
        $this->addColumn('magedefaultattribute', ['label' => __('Product attribute'), 'size' => 28]);
        $this->addColumn('value', ['label' => __('Default Value'), 'size' => 28]);
        
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Line');
        parent::_construct();
    }
    
   public function renderCellTemplate($columnName)
    {
         $options = [];
         if ($columnName == 'magedefaultattribute' && isset($this->_columns[$columnName])) {
              $optionsAttribute = $this->_eavConfig->getEntityAttributeCodes('catalog_product');
              foreach($optionsAttribute as $code){
                $options[]= ['value'=>$code ,'label'=>$code];
               }
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
