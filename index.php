<?php
require_once __DIR__.'/inc/Curl.php';
use \Curl\Curl;

$dev = true;
if ($dev) {
	error_reporting(-1);
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 'On');
}

function authAction($url, $response, $params = []) {
	$api_key = "";
	$api_secret = "";

	$c = new Curl();
	$c->get('https://sms.monsite.fr/auth.php', [
		'api_key' => $api_key,
		'api_secret' => $api_secret
	]);
	$tokenSerial = $c->response;
	if (isset($tokenSerial->token)) {
		$token = $tokenSerial->token;
		$c = new Curl();
		$params['api_key'] = $api_key;
		$params['token'] = $token;
		$c->get($url, $params);
		$response($c->response);
	}
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'queue') {

	$sms = [
		'receiverNumber' => '06xxxxxxxx',
		'message' => 'The quick brown fox jumps over the lazy dog',
		'date' => time()
	];

	authAction('https://sms.monsite.fr/sms.php', function($response) {}, [
		'sms_data' => base64_encode(json_encode($sms))
	]);
} else if ($action == 'unqueue') {
	authAction('https://sms.monsite.fr/read.php', function($response) {
		var_dump($response);
	});
}
?>
