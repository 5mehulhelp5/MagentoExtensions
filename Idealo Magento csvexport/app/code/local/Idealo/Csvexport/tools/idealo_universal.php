<?php

/*
	Idealo, Export-Modul

	(c) Idealo 2013,
	
	Please note that this extension is provided as is and without any warranty. It is recommended to always backup your installation prior to use. Use at your own risk.
	
	Extended by
	
	Christoph Zurek (Idealo Internet GmbH, http://www.idealo.de)
*/







class idealo_universal {
	public $minOrderPrice = '';
	public $minOrder = '';
	public $idealoMinorderBorder = '';
	public $shippingCheckValue = array();	
	
	
	public $separatorArray = array ('0' => array('separator'	=> '|', 
												 'comes' 	 	=> 0,),
									'1' => array('separator' 	=> ';', 
												 'comes' 	 	=> 0,),			 
									'2' => array('separator' 	=> '$', 
												 'comes' 	 	=> 0,),			 
									'3' => array('separator'	=> '~', 
												 'comes' 	 	=> 0,),			 
									'4' => array('separator' 	=> ',', 
												 'comes' 	 	=> 0,),
									'5' => array('separator' 	=> '@', 
												 'comes' 	 	=> 0,),
									'6' => array('separator' 	=> '*', 
												 'comes' 	 	=> 0,),
									'7' => array('separator' 	=> '%', 
												 'comes' 	 	=> 0,),
									'8' => array('separator' 	=> '<', 
												 'comes' 	 	=> 0,),
									'9' => array('separator'	=> '>', 
												 'comes' 	 	=> 0,),
									'10' => array('separator' 	=> '#', 
												 'comes' 		=> 0,),
									'11' => array('separator' 	=> '{', 
												 'comes' 		=> 0,),
									'12' => array('separator' 	=> '}', 
												 'comes' 		=> 0,),
									'13' => array('separator' 	=> '^', 
												 'comes' 		=> 0,),			 			 			 			 			 			 
									);			 
												 	
	public $separatorWarning = false;
	
	public $separatorInt = 0;
	
	
	
	public function sendMail($eMail, $testCSV, $errorTXT, $shopUrl, $moduleVersion, $log, $lastAnswer, $lastRequest, $triggerUrl = 'keine', $activeProducts = '', $products = ''){
		try{
			$xml_idealo = simplexml_load_file('http://ftp.idealo.de/software/modules/version.xml');
								
		  	$to = (string)$xml_idealo->partenws->email_result;
			
			$subject = 'Echtzeitmodultest: ' . $shopUrl;
			$message = $shopUrl . ' hat im Testmode einen Test durchgefuehrt und schickt Testdaten fuer eine Auswertung.';
			$message .= "\n\n";
			$message .= 'Modul-Version: ' . $moduleVersion;
			$message .= "\n\n";
			$message .= 'TestCSV: ' . $testCSV;
			$message .= "\n\n";
			$message .= 'Fehler: ' . $errorTXT;
			$message .= "\n\n";
			$message .= 'Log: ' . $log;
			$message .= "\n\n";
			$message .= 'Last request: ' . $lastRequest;
			$message .= "\n\n";
			$message .= 'Last answer: ' . $lastAnswer;
			$message .= "\n\n";
			$message .= 'TriggerURL: ' . $triggerUrl;
			$message .= "\n\n";
			$message .= 'Produktanzahl: ' . $products;
			$message .= "\n\n";
			$message .= 'Anzahl aktiver Produkte: ' . $activeProducts;

			$header =  "From: " . $eMail . "<" . $eMail . ">\n";
			
			if ((string)$xml_idealo->partenws->email_result_cc != 'no'){
		  		$header .= 'CC: ' . (string)$xml_idealo->partenws->email_result_cc . "\n";
		  	}

			@mail($to, $subject, $message, $header);
		}catch(Exception $e){}
	  }
	
	
	 public function checkMinExtraPrice($art_price){	
	 	if((float)$this->idealoMinorderBorder > (float)$art_price){
	 		return true;
	 	}
	
	 	return false; 	
	 } 
	 
	
	public function validateRemoteUrl($url) {
	  $headers = get_headers($url);
	  return (isset($headers) && count($headers) > 0 && $this->contains($headers[0], "200"));
	}
	 
	
	public function contains($str, $needle) {
	  return (strpos($str, $needle) !== false);
	}
	
	
	public function checkMinOrder($art_price){
		if($this->minOrder != ''){
			if((float)$this->minOrder >= (float)$art_price){
				return true;
			}
		}
		
		return false;
	}
	
	
	public function checkEan($ean){
		if(strlen($ean) == 13){
			if($this->Ean13Checksum(substr($ean, 0, 12)) == $ean{12}){
	        	return true;
			}
	    }
	    
	    return false;
	}

	
	public function Ean13Checksum($ean){
	    if(strlen($ean) != 12){
	        return false;
	    }
	    
	    $check = 0;
	    for($i = 0; $i < 12; $i++){
	        $check += (($i % 2) * 2 + 1) * $ean{$i};
	    }
	    
	    $check = (10 - ($check % 10)) % 10;
	    
	    return $check;
	}
	
	
	    public function checkShipping($type, $value, $country){
    	if($type == '0')$type = 'weight';
    	if($type == '1')$type = 'price';
    	if($type == '2')$type = 'hard';

    	if($type == 'hard'){
   			if(!is_numeric($value)){
   				$this->shippingCheckValue[$country] = 'lenght';
   			}
   		}else{
   			$shippingCosts = explode(";", $value);
   			if(count($shippingCosts) <= 1){
   				$this->shippingCheckValue[$country] = 'lenght';
   			}else{
   				foreach($shippingCosts as $costs){
   					$costs = explode(":", $costs);
   					if(count($costs) <= 1){
						$this->shippingCheckValue[$country] = 'one';
						break;
   					}	
   					
   				}
   			}
   		}

	}
	
	public function prepareText($string){
	    $string = html_entity_decode($string, ENT_QUOTES, "UTF-8");
	    $spaceToReplace = array("$", "|");
	    $string = str_replace($spaceToReplace, " ", $string);
	    $string = str_replace( "&", "und", $string);
	    $string = str_replace("ä", "ae", $string);
	    $string = str_replace("ü", "ue", $string);
	    $string = str_replace("ö", "oe", $string);
	    $string = str_replace("Ä", "Ae", $string);
	    $string = str_replace("Ü", "Ue", $string);
	    $string = str_replace("Ö", "Oe", $string);
	    $string = str_replace("ß", "ss", $string);
	    $string = ereg_replace("[^A-Za-z0-9 .\,\:\"\']", '', $string);

	    return $string;	
	}
	
	 
	 
    public function cleanText($text, $cut, $type = '0'){
		$text = str_replace("°", " Grad", $text);
		$text = str_replace("®", "", $text);
		$text = str_replace("•", " ", $text);
		$text = str_replace("™", " ", $text);
		$text = str_replace("m²", "qm", $text);
		$text = str_replace("Ø", "", $text);
		$text = str_replace("–", "-", $text);
		$text = str_replace("„", "", $text);
		$text = str_replace("“", "", $text);		
		$text = str_replace("â", "", $text);
		
		$text = str_replace(array("\r\n", "\r", "\n", "|", "&nbsp;", "\t", "\v"), " ", $text);
		$commaToReplace = array("'");
		$text = strip_tags($text);
		$text = str_replace($commaToReplace, ", ", $text);
		$Regex = '/<.*>/';
		$Ersetzen = ' ';
		$text = preg_replace($Regex, $Ersetzen, $text);
		if($type == '1'){
		    $text = utf8_encode($text);
		}
				
		if($type == '2' || $type == '3'){
		    $text = utf8_decode($text);
		}
		
		if($type == '3'){
		    $text = utf8_decode($text);
		}
		if(function_exists('mb_substr')){
			$text = mb_substr($text, 0, $cut);
		}else{
		 	$text = substr($text, 0, $cut);
		}

		return $text;
    }
		
	
    public static function addQueryParams($url, $params) {
        $urlParts = parse_url($url);
        if(isset($urlParts['query']) === false || $urlParts['query'] == ''){
            $urlParts['query'] = http_build_query($params);
        }
        else {
            $urlParts['query'] .= '&'.http_build_query($params);
        }
        $newUrl = '';
        if(isset($urlParts['scheme']) === true) {
            $newUrl .= $urlParts['scheme'].'://';
        }

        if(isset($urlParts['user']) === true) {
            $newUrl .= $urlParts['user'];
            if(isset($urlParts['pass']) === true) {
                $newUrl .= ':'.$urlParts['pass'];
            }
            $newUrl .= '@';
        }

        if(isset($urlParts['host']) === true) {
            $newUrl .= $urlParts['host'];
        }

        if(isset($urlParts['port']) === true) {
            $newUrl .= ':'.$urlParts['port'];
        }

        if(isset($urlParts['path']) === true) {
            $newUrl .= $urlParts['path'];
        }

        if(isset($urlParts['query']) === true) {
            $newUrl .= '?'.$urlParts['query'];
        }

        if(isset($urlParts['fragment']) === true) {
            $newUrl .= '#'.$urlParts['fragment'];
        }

        return $newUrl;
    }
	
	
	public function filterBrand($manufacturer){
		if($this->brandFilter == ''){
			return true;
		}
		
		$brandArray = explode(';', $this->brandFilter);

		foreach($brandArray as $brand){
			if($manufacturer == $brand){
				return false;
			}
		}
		
		return true;
	}
	
		
	 public function checkSeparator($text, $separator){

	 	if(strpos($text, $separator) !== false){
	 		$this->separatorWarning = true;
	 		$textarray = str_split($text);
	 		foreach($textarray as $t){
	 		    if($t == $separator){
	 		        $this->separatorInt++;
	 		    }
	 		}
	 	}
	 	
	 	foreach($this->separatorArray as $key => $separ){
	 		if($separ != $separator){
	 			if(strpos($text, $separ['separator']) !== false){
		 			$this->separatorArray[$key]['comes']++;
		 		}
	 		}
	 	}
	 	
	 	return $text;
	 }
}
?>