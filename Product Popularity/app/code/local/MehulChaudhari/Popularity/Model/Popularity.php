<?php
class MehulChaudhari_Popularity_Model_Popularity extends Mage_Core_Model_Abstract
{



/** this function will returns a product views counts
params product id,store id.
**/ 

public function getViews($product,$store){
              $popularity['views'] = 0;
              $reports = Mage::getResourceModel('reports/product_collection')
                       ->addViewsCount()
                       ->setStoreId($store)
                       ->addStoreFilter($store)
                       ->addFieldToFilter('entity_id', $product);
              foreach($reports as $report)
                  {
                           $popularity['views'] = $report->getViews(); 
                  }

                  return $popularity['views'];
}

/** this function will returns a product reviews counts
params product id,store id.
**/ 

public function getRating($product,$store){
                
				$filter = Mage::helper('popularity')->getFilterEnable($store);
				if($filter){
					$popularity['reviews'] = 0;
					$percent = Mage::helper('popularity')->getFilterPercent($store);
					if($percent == NULL)$percent = 50;
				
					$productReviews = Mage::getModel('review/review')->getResourceCollection()
								   ->addStoreFilter($store)
								   ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
								   ->addEntityFilter('product', $product)
								   ->setDateOrder()
								   ->addRateVotes();
					$customerReviews = $productReviews->getItems(); 
						if (count($customerReviews) && count($productReviews)){
						    $total = 0; $avg = 0;
						    foreach($customerReviews as $reviewPop){
							    $ratings = array();
								$votes = $reviewPop->getRatingVotes();
								if (count($votes)){
									foreach ($votes as $vote){
										$ratings[] = $vote->getPercent();
									}
									$avg = ceil(array_sum($ratings)/count($ratings));
									if($avg > $percent){
										$total++; 
									}
								}
							}
						  return $total;
						}
					return $popularity['reviews'];
				}else{
					$popularity['reviews'] = 0;
					$summaryData = Mage::getModel('review/review_summary')->setStoreId($store)->load($product);
					$reviewCount = $summaryData->getReviewsCount();
					$reviewSummary = $summaryData->getRatingSummary();
						if($reviewCount != NULL)
						{
							$popularity['reviews'] = $reviewCount;
						}
					return $popularity['reviews'];
				}
}

/** this function will returns a product sells counts
params product id,store id.
**/ 

public function getSell($product,$store){
               $popularity['sales'] = 0;
               $salesreport = Mage::getResourceModel('reports/product_collection')  
                            ->addOrderedQty()
                            ->setStoreId($store)
                            ->addStoreFilter($store)
                            ->addFieldToFilter('entity_id', $product);
              foreach($salesreport as $sales)
                 {
                           $popularity['sales'] = $sales->getOrderedQty();
                 }
                 return $popularity['sales'];
}

/** this function will returns a product popularity
return array
params product id,store id.
**/ 
public function getPopularity($product,$store){
	$pop = array();
	$sell = Mage::helper('popularity')->getSell($store);
	$rating = Mage::helper('popularity')->getRating($store);
	$view = Mage::helper('popularity')->getViews($store);

	if($sell){
             $pop['sales'] = $this->getSell($product,$store);
	}else{
             $pop['sales'] = 0;
	}

	if($rating){
             $pop['reviews'] = $this->getRating($product,$store);
	}else{
             $pop['reviews'] = 0;
	}

	if($view){
             $pop['views'] = $this->getViews($product,$store);
	}else{
             $pop['views'] = 0;
	}
        return $pop;
} 

}
