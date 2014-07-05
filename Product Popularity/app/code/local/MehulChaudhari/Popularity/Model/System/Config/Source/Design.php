<?php

class MehulChaudhari_Popularity_Model_System_Config_Source_Design
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'orange', 'label'=> Mage::helper('popularity')->__('Orange')),
            array('value' => 'gray', 'label'=> Mage::helper('popularity')->__('Gray')),
            array('value' => 'blue', 'label'=> Mage::helper('popularity')->__('Blue')),
        );
    }


}
