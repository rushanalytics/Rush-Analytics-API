<?php
/****************************************************************** create suggest project example**************************************************/

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

$name = 'test api suggest';
$yandex_region = '213';
$google_country = '.ru';
$google = 1;
$yandex = 1;
$mail = 1; 
$maxdepth = 3; // max depth to parse (1-3)
$normal = 1; // parse keywords without suffix
$space = 1; // parse keywords with space suffix "keyword "
$enalpha = 1; // parse keywords with a-z suffix (english alphabet)
$rualpha = 1; // parse keywords with a-ja suffix (russian alphabet)
$numalpha = 1; // parse keywords with 0-9 suffix
$swtype = 0;

$youtube = 1; // new parameter -0/1
$google_lang = 'en'; // google language
$youtube_country = '.com'; // youtube country
$youtube_lang = 'en'; // youtube language


// array of strings - keywords you want to parse + stopwords
$keywords = array('wilgood', 'Схема проезда');
$stopwords = array('сервис');

$response = $api->rushapi__create_suggest_project(
		$hash,
		$name,
		$google,
		$yandex,
		$mail,
		$google_country,
		$yandex_region,
		$maxdepth,
		$normal,
		$space,
		$enalpha,
		$rualpha,
		$numalpha,
		$keywords,
		$stopwords,
		$swtype,
		$youtube,
		$google_lang,
		$youtube_country,
		$youtube_lang	
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

$status = $api->rushapi__suggest_project_status($hash,$sessionid);
print $status;
/*

possible responses are:  status code | additional info (without '')
'1'
'2|status'
'3'
'4|link to xlsx output'
'6'
'7'

for example 
'2|144/700 in lvl 2' - parsing project in depth 2 144/700 suggests parsed
'4|http://www.rush-analytics.ru/apioutput.php?type=7&sessionid=a066f37c60d0fc59f90b1ef756b54b1b'

status codes:

1 - in queue
2 - parsing
3 - error
4 - done
6 - loading keywords
7 - saving keywords (creating xlsx output)

*/


?>
