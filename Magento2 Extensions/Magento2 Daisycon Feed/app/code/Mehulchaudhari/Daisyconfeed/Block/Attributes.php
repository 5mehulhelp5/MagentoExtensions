<?php
namespace Mehulchaudhari\Daisyconfeed\Block;
class Attributes extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
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
    
        $this->addColumn('mageattribute', ['label' => __('Magento product attribute'), 'size' => 28]);
        $this->addColumn('feedattribute', ['label' => __('Feed Tags'), 'size' => 28]);
        
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Line');
        parent::_construct();
    }
    
   public function renderCellTemplate($columnName)
    {
         $options = [];
         if ($columnName == 'mageattribute' && isset($this->_columns[$columnName])) {
              $optionsAttribute = $this->_eavConfig->getEntityAttributeCodes('catalog_product');
              foreach($optionsAttribute as $code){
                $options[]= ['value'=>$code ,'label'=>$code];
               }
          }else if($columnName == 'feedattribute' && isset($this->_columns[$columnName])){
              $optionsAttribute = $this->getFeedTags();
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
    
    public function getFeedTags()
    {
                return [
        'internal_id',
        'title',
        'description',
		'img_large',
        'img_medium',
		'img_small',
		'minimum_price',
        'maximum_price',
        'color',
        'stock',
        'priority',
        'size'
		];
    }
}
