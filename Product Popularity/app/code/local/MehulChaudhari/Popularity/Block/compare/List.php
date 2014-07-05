<?php
class MehulChaudhari_Popularity_Block_Compare_List extends Mage_Catalog_Block_Product_Compare_List
{
    public function getPopularityHtml($product){
        $this->setTemplate('popularity/popularitylist.phtml');
        $this->setProductID($product);
        return $this->toHtml();
   }
}