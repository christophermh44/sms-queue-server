<?php
require_once __DIR__.'/inc/Auth.php';
require_once __DIR__.'/inc/JsonData.php';
require_once __DIR__.'/inc/Sms.php';

$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

Auth::authenticate($api_key, $token, function() {
	JsonData::out(Sms::retrieveAllSms());
});
