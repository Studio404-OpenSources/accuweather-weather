<?php 
/* define dir */
define("DIR", __DIR__);

/* Require Class file */
require_once('studio404_weather.php'); 

$studio404_weather = new studio404_weather(); 
$array = $studio404_weather->lunch(
	'http://www.accuweather.com/en/ge/tbilisi/171705/weather-forecast/171705', /* You Have To Change Only Url */
	'temp/', 
	array(
		"condition"=>"cond", 
		"celsius"=>"temp"
	)
);

$error = $studio404_weather->error; /* Error message Array */
/* Print */
if(empty($error)){ /* if error array is empty */
	printf('Weather Condition: %s <br />', $array['condition']);
	printf('Weather in celsius: %s <br />', $array['celsius']);
	printf('Weather in Fahrenheit: %s° <br />', studio404_weather::celToFahren($array['celsius']));
}else{
	echo "<pre>";
	print_r($error);
	echo "</pre>";
}
?>
