<?php 
require_once('studio404_weather.php'); 

/* You Have To Change Only Url */
$studio404_weather = new studio404_weather(); 
$array = $studio404_weather->lunch(
	'http://www.accuweather.com/en/ge/tbilisi/171705/weather-forecast/171705', 
	'temp/', 
	array(
		"condition"=>"cond", 
		"celsius"=>"temp"
	)
);
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