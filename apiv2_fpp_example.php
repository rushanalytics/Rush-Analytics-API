<?php

function curl_fn($url,$post_args=false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if($post_args) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post_args));
    }    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec ($ch);    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    return array($http_code,$response);
}

$base_api_url = 'https://www.rush-analytics.ru/apiv2';

$api_key = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // your api key
$keywords = array('wilgood');
$name = 'test api v2 fpp';
$url = 'wilgood.ru';

$ya_region1 = new stdClass();
$ya_region1->type = '.ru';
$ya_region1->id = 213;
$ya_region1->lang = 'ru';

$ya_region2 = new stdClass();
$ya_region2->type = '.ru';
$ya_region2->id = 225;
$ya_region2->lang = 'ru';

$go_region1 = new stdClass();
$go_region1->type = '.ru';
$go_region1->id = 21250;
$go_region1->lang = 'ru';

$yandex_regions = array(
    $ya_region1,
    $ya_region2,
);

$google_regions = array(
    $go_region1,
);

$post_args = array(
    'apikey' => $api_key,
    'name' => $name,
    'url' => $url,
    'keywords' => $keywords,
    'yandexRegions' => $yandex_regions,
    'googleRegions' => $google_regions,
);

// url in format https://www.rush-analytics.ru/apiv2/create/fpp
$curl_ret = curl_fn($base_api_url . '/create/fpp', $post_args);

list($http_code,$json_response) = $curl_ret;
$json_decoded_response = json_decode($json_response);


// Project created
if($http_code == 201) {
	// to get status of project only post GET request with url in format https://www.rush-analytics.ru/apiv2/status/{type}/{id}?apikey={32char_api_key}
    $curl_status_ret = curl_fn($base_api_url . '/status/' . $json_decoded_response->type . '/' . $json_decoded_response->id . '?apikey=' . $api_key);
	list($http_code_status,$json_response_status) = $curl_status_ret;
	$json_decoded_response_status = json_decode($json_response_status);
	
	print 'Project created with id: ' . $json_decoded_response->id . '<br>';
	print 'Project status: ' . $json_decoded_response_status->status;
	
}
// Bad input
if($http_code == 400) {
    print 'Invalid input: <br>';
    print $json_response;
}

?>
