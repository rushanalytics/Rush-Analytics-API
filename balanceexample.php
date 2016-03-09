<?php

include_once('nusoap.php'); // change this to your nusoap location

ini_set("soap.wsdl_cache_enabled", 0);
$wsdl="http://www.rush-analytics.ru/api.php?wsdl";
$api = new SoapClient($wsdl);
$api->response_timeout = 60000;


$hash = 'XXXXXXXXXXXXXXXXXXXXXX'; // API code

$test = $api->rushapi__get_balance($hash);
var_dump($test);

?>	