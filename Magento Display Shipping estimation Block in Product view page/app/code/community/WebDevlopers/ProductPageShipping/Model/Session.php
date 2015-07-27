<?php

class WebDevlopers_ProductPageShipping_Model_Session extends Mage_Core_Model_Session_Abstract
{
    const EXTENSIONNAMESPACE = 'productpageshipping';

    public function __construct()
    {
        $this->init(self::EXTENSIONNAMESPACE);
    }
}
