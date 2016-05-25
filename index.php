<?php 
require_once('studio404_weather.php'); 
/* You Have To Change Only Url */
$url = 'http://www.accuweather.com/en/ge/gudauta/166775/weather-forecast/166775';
$tempPath = "temp/";
$classArray = array(
	"condition"=>"cond", 
	"celsius"=>"temp"
);
$studio404_weather = new studio404_weather(); 
$array = $studio404_weather->lunch($url, $tempPath, $classArray);

// print
echo "Weather Condition: ".$array['condition']."<br />";
echo "Weather in celsius: ".$array['celsius']."<br />";
echo "Weather in Fahrenheit: ".studio404_weather::celToFahren($array['celsius'])."° <br />";
?>