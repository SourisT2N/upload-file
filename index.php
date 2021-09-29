<?php 
	if(empty($_SESSION))
	{
		session_name('_sid');
		session_start();
	}
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	require_once "config/config.php";
	require_once "vendor/autoload.php";
	require_once APP_PATH . "router.php";