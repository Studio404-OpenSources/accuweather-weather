<?php 
require_once('studio404_weather.php'); 
/* You Have To Change Only Url */
$url = 'http://www.accuweather.com/en/ge/tbilisi/171705/weather-forecast/171705'; // Tbilisi
$tempPath = "temp/"; /* temporary file path, you clean it as many times as you with via cron job */
$classArray = array( /* class array : "whatever you want" => classname */
	"condition"=>"cond", 
	"celsius"=>"temp"
);
$studio404_weather = new studio404_weather(); 
$array = $studio404_weather->lunch($url, $tempPath, $classArray);
$error = $studio404_weather->error; /* Error message Array */

/* Print */
if(empty($error)){ /* if error array is empty */
	echo "Weather Condition: ".$array['condition']."<br />";
	echo "Weather in celsius: ".$array['celsius']."<br />";
	echo "Weather in Fahrenheit: ".studio404_weather::celToFahren($array['celsius'])."° <br />";
}else{
	echo "<pre>";
	print_r($error);
	echo "</pre>";
}
?>