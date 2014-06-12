<?php
	require_once __DIR__.'/inc/Auth.php';
	require_once __DIR__.'/inc/JsonData.php';

	$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
	$api_secret = isset($_GET['api_secret']) ? $_GET['api_secret'] : '';

	JsonData::out(Auth::proceed($api_key, $api_secret));
?>
