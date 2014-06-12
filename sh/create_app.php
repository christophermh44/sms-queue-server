<?php
	require_once __DIR__.'/../inc/Auth.php';

	JsonData::out(Auth::createApp());
?>
