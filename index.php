<?php 
class studio404_weather{
	public $url; 
	public $city; 
	public $celsius; 

	public function lunch($url, $tempPath, $classArray){
		$this->url = $url;
		$this->tempPath = $tempPath;
		$this->classArray = $classArray;
		$getHtml = $this->getHtmlDom(); 
		$parseHtml = $this->parseHtml($getHtml);
		return $parseHtml;
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
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
		$html = curl_exec($curl);
		curl_close($curl);
		$file = $this->tempPath.$this->urlToMd5($this->url).'.html';
		file_put_contents($file, $html);
		return $file;
	}

	public function urlToMd5($url){
		$out = md5($url);
		return $out;
	}

	public function parseHtml($file){
		$out = array();
		$html = file_get_contents($file); 
		$internalErrors = libxml_use_internal_errors(true);

		$domdocument = new DOMDocument();
		$domdocument->loadHTML($html);
		libxml_use_internal_errors($internalErrors);
		$DOMXPath = new DOMXPath($domdocument);

		$contains = $this->contains($DOMXPath);
		foreach ($this->classArray as $key => $value) {
		$out[$key] = $contains[$key]->item(0)->nodeValue;
		}
		return $out;
	}

	public function contains($DOMXPath){
		$out = array();
		foreach ($this->classArray as $key => $value) {
			$out[$key] = $DOMXPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $value ')]");
		}
		return $out;
	}


}

/* You Have To Change Only Url */
$url = 'http://www.accuweather.com/en/ge/gudauta/166775/weather-forecast/166775';
$tempPath = "temp/";
$classArray = array(
	"condition"=>"cond", 
	"celsius"=>"temp"
);
$studio404_weather = new studio404_weather(); 
$array = $studio404_weather->lunch($url, $tempPath, $classArray);

echo "<pre>"; 
print_r($array);
?>