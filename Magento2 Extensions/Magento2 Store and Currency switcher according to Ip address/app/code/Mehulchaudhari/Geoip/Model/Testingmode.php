<?php

namespace Mehulchaudhari\Geoip\Model;

class Testingmode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
                  ['value' => 0, 'label' => __('No one (Disabled)')], 
                  ['value' => 1, 'label' => __('Current Administrators')],
                  ['value' => 2, 'label' => __('Specific IP Addresses')],
                  ['value' => 3, 'label' => __('Everyone')]
               ];
    }
}
