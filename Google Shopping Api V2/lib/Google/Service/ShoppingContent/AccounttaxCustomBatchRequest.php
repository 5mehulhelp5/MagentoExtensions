<?php
class Google_Service_ShoppingContent_AccounttaxCustomBatchRequest extends Google_Collection
{
  protected $collection_key = 'entries';
  protected $internal_gapi_mappings = array(
  );
  protected $entriesType = 'Google_Service_ShoppingContent_AccounttaxCustomBatchRequestEntry';
  protected $entriesDataType = 'array';


  public function setEntries($entries)
  {
    $this->entries = $entries;
  }
  public function getEntries()
  {
    return $this->entries;
  }
}