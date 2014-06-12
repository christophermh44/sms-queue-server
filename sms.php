<?php
require_once __DIR__.'/inc/Auth.php';
require_once __DIR__.'/inc/Sms.php';
global $smsData;

$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';
$smsData = isset($_GET['sms_data']) ? $_GET['sms_data'] : '';

Auth::authenticate($api_key, $token, function() {
	global $smsData;
	$smsSerialized = base64_decode($smsData);
	$sms = Sms::fromJson($smsSerialized);
	Sms::queueSms($sms);
});
?>
