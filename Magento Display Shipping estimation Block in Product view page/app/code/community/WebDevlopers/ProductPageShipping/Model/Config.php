<?php

class WebDevlopers_ProductPageShipping_Model_Config
{
    
    const XML_PATH_ENABLED = 'webdevlopers_productpageshipping/settings/enabled';


    
    const XML_PATH_USE_COUNTRY = 'webdevlopers_productpageshipping/settings/use_country';

    
    const XML_PATH_USE_REGION = 'webdevlopers_productpageshipping/settings/use_region';

    
    const XML_PATH_USE_CITY = 'webdevlopers_productpageshipping/settings/use_city';

   
    const XML_PATH_USE_POSTCODE = 'webdevlopers_productpageshipping/settings/use_postcode';

    
    const XML_PATH_USE_COUPON_CODE = 'webdevlopers_productpageshipping/settings/use_coupon_code';

    
    const XML_PATH_USE_CART = 'webdevlopers_productpageshipping/settings/use_cart';

    
    const XML_PATH_USE_CART_DEFAULT = 'webdevlopers_productpageshipping/settings/use_cart_default';


    
    const XML_PATH_DEFAULT_COUNTRY = 'shipping/origin/country_id';


    
    const XML_PATH_CONTROLLER_ACTIONS = 'webdevlopers/productshippingpage/controller_actions';

    
    const XML_PATH_DISPLAY_POSITION = 'webdevlopers_productpageshipping/settings/display_position';

    
    const DISPLAY_POSITION_RIGHT = 'right';
    const DISPLAY_POSITION_LEFT = 'left';

    
    const LAYOUT_HANDLE_LEFT = 'webdevlopers_productpageshipping_left';
    const LAYOUT_HANDLE_RIGHT = 'webdevlopers_productpageshipping_right';

    
    public function useCountry()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_COUNTRY);
    }

    
    public function useRegion()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_REGION);
    }

    
    public function useCity()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CITY);
    }

    
    public function usePostcode()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_POSTCODE);
    }

    
    public function useCouponCode()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_COUPON_CODE);
    }

    
    public function useCart()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CART);
    }

    
    public function useCartDefault()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_USE_CART_DEFAULT);
    }


   
    public function getDefaultCountry()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_COUNTRY);
    }

    
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }

    
    public function getDisplayPosition()
    {
        return Mage::getStoreConfig(self::XML_PATH_DISPLAY_POSITION);
    }

    
    public function getControllerActions()
    {
        $actions = array();
        foreach (Mage::getConfig()->getNode(self::XML_PATH_CONTROLLER_ACTIONS)->children() as $action => $node) {
            $actions[] = $action;
        }

        return $actions;
    }
}
