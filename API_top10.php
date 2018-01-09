<?php

include_once('nusoap.php');

ini_set("soap.wsdl_cache_enabled", 0);
$wsdl="http://www.rush-analytics.ru/api.php?wsdl";
$api = new SoapClient($wsdl);
$api->response_timeout = 60000;


$hash = 'xxxxxxxxxxxxxxxxxxxxxx'; // Ваш 32х значный API ключ !!! ВВЕДИТЕ СВОЙ КЛЮЧ !!!
$name = 'test api top10 go 1'; // Переменная отвечает за имя проекта

// Если поисковая система Google
$provider = 1;
// Если поисковая система Yandex
$provider = 0;

$country = '.ru'; // Страна
$region =  ''; // Регион для поисковой системы Google оставляем пустым
$region =  213; // Регион для поисковой системы Yandex 213 Для Москвы, 225 for для России, ...
$lang = 'ru';  // Язык
$newlogic = 1; // Эксперт опция для сбора данных в Google

// в поисковой системе Google
$params = array(
	1, // Количество найденных документов 
	0, // Собрать ключевые слова
	0,
	0,
	0,
	20 // Глубина (10,20,30,40,50,60,70,80,90,100)	
);

// if yandex
$params = array(
	0, // Количество найденных документов  
	0, // Собрать ключевые слова 
	0, // Сбор количества объявлений в Яндекс Директ 
	1, // Регион 
	1, // Подсчитать расширенные сниппеты 
	20 // Глубина (10,20,30,40,50,60,70,80,90,100)	
);


$keywords = array('wilgood','wilbad');

$test = $api->rushapi__create_top10_project($hash, $name, $keywords, $provider, $country, $region, $lang, $newlogic, $params);
if(strpos($test,'ERR') !== false) {
	// Собирать ошибки
}
else {
	$sessionid = $test;
}

$test = $api->rushapi__top10_project_status($hash,$sessionid);
var_dump($test);

?>	
