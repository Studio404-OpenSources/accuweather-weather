# Weather From http://www.accuweather.com/
You have to chnage only URL and viola ...
```php
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
```
