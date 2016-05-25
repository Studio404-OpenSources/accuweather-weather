<?php 
/**
 * Accuweather.com Weather
 *
 * PHP version 5.6.21
 *
 * @package Weather
 * @author Giorgi Gvazava <giorgigvazava87@gmail.com>
 * @copyright 2016 - 2017 Giorgi Gvazava
 */
class studio404_weather{
	public $url;  
	public $error = array();

	public function lunch($url, $tempPath, $classArray, $itemNumber = 0){
		if (filter_var($url, FILTER_VALIDATE_URL) === false) {
			$this->error[] = "Invalid Url !";
		}else if(!is_dir($tempPath)){
			$this->error[] = "Invalid Directory Path !";
		}else if(!is_array($classArray)){
			$this->error[] = "Invalid Class Array Variable !";
		}else{
			$this->url = $url;
			$this->tempPath = $tempPath;
			$this->classArray = $classArray;
			$this->itemNumber = $itemNumber;
			$getHtml = $this->getHtmlDom(); 
			if(!empty($getHtml)){
				$parseHtml = $this->parseHtml($getHtml);
				return $parseHtml;
			}
			$this->error[] = "Could not recive data from the url !";
			return false;
		}
	}

	public function getHtmlDom(){
		$curl = curl_init();
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.12011-10-16 20:23:00");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_REFERER, $this->url);
		curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
		$html = curl_exec($curl);
		curl_close($curl);
		$file = $this->tempPath.$this->urlToMd5($this->url).'.html';
		if(@file_put_contents($file, $html)){
			return $file;	
		}
		$this->error[] = "Could not save file in temporary folder !"; 
		return false;		
	}

	public function urlToMd5($url){
		if(!empty($url)){
			$out = md5($url);
			return $out;
		}
		$this->error[] = "Could not convert empty string to md5 !";
		return false;
	}

	public function parseHtml($file){
		$out = array();
		if(file_exists($file)){
			$html = file_get_contents($file); 
			$internalErrors = libxml_use_internal_errors(true);

			$domdocument = new DOMDocument();
			$domdocument->loadHTML($html);
			libxml_use_internal_errors($internalErrors);
			$DOMXPath = new DOMXPath($domdocument);
		
			$contains = $this->contains($DOMXPath);
			foreach ($this->classArray as $key => $value) {
				if(is_object($contains[$key]->item($this->itemNumber)) && property_exists($contains[$key]->item($this->itemNumber), 'nodeValue')){
					$out[$key] = $contains[$key]->item($this->itemNumber)->nodeValue;
				}else{
					$this->error[] = "Could not get class !";
					return false;
				}				
			}
			return $out;
		}
		$this->error[] = "Could not get temp file !";
		return false;
	}

	public function contains($DOMXPath){
		$out = array();
		foreach ($this->classArray as $key => $value) {
			$out[$key] = $DOMXPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $value ')]");
		}
		return $out;
	}

	public static function celToFahren($celsius){
		$fahren = 0;
		try{
			$fahren = ((int)$celsius * 9/5) + 32;
		}catch(Exception $e){
			$this->error[] = $e;
		}
		return $fahren;
	}

}