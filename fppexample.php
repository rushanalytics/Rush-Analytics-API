<?php
/***************************************************************** create fast positions project example*********************************************/

/*
our api soap server
http://www.rush-analytics.ru/api.php?wsdl 
*/

// you need to include some sort of soap library (we use nusoap) http://sourceforge.net/projects/nusoap/
include('nusoap/nusoap.php'); // !!! CHANGE THIS !!!

ini_set("soap.wsdl_cache_enabled", 0);
$wsdl="http://www.rush-analytics.ru/api.php?wsdl";
$api = new SoapClient($wsdl);
$api->response_timeout = 60000;

$hash = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // your 32 characters long API code !!! CHANGE THIS !!!

// array of yandex regions each has domain (.ru,.ua,.kz,.by), region number and language you want to parse
$yaregions = array(
	array('domain' => '.ru', 'regionid' => 213, 'regionlang' => 'ru'),
	array('domain' => '.ru', 'regionid' => 225, 'regionlang' => 'ru'),
	array('domain' => '.ua', 'regionid' => 145, 'regionlang' => 'ru'),
);

// array of google regions each has domain (.ru,.cz,.com,...), region number, and language you want to parse
$goregions = array(
	array('domain' => '.ru', 'regionid' => 21250, regionlang' => 'ru'),
	array('domain' => '.cz', 'regionid' => 21494, regionlang' => 'cs'),
);

$name = 'test api fpp';
$url = 'wilgood.ru';

$google = 1;
$yandex = 1;

// array of strings - keywords you want to parse
$keywords = array('wilgood', 'Схема проезда');

$response = $api->rushapi__create_fpp_project(
	$hash,
	$name,
	$url,
	$google,
	$yandex,
	$yaregions,
	$goregions,
	$keywords	
);

/* 
in variable $response is now either error message or SESSIONID of your newly created project SAVE YOUR SESSIONID for future references to this project (getting status,result file...)

ERROR_BAD_USERID - probably bad API key
ERROR_IP_NOT_ALLOWED - you have not listed your ip in allowed ips field in your API settings, either leave allowed ips field empty, or add your actual ip
ERROR_LOW_BALANCE - you have not enough balance to parse inserted project
ERROR_SERVER - error is at our side (please contact us at support@rush-agency.ru

*/

if(strpos($response,'ERROR') !== false) {
	// handle error
	exit(1);
}
else {
	$sessionid = $response;
}

$status = $api->rushapi__fpp_project_status($hash,$session);
print $status;
/*

possible responses are:  status code | additional info (without '')
'1'
'2'
'3'
'4|link to xlsx output'
'6'
'7'

for example 
'4|http://www.rush-analytics.ru/apioutput.php?type=1&sessionid=a066f37c60d0fc59f90b1ef756b54b1b'

status codes:

1 - in queue
2 - parsing
3 - error
4 - done
6 - loading keywords
7 - saving keywords (creating xlsx output)

*/
