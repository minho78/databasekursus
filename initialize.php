<?php
	define("LOCAL",true); /* Set true for local development, false for release. */
	if (LOCAL)
		$conn = new PDO("mysql:host=localhost;dbname=fanzy_db;charset=UTF8", "root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	else
		$conn = new PDO("mysql:host=localhost;dbname=ahndk_com", "ahndk_com","3EHXuFJG");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	define("PRIVATE_PATH",dirname(__FILE__));
	define("PROJECT_PATH",dirname(PRIVATE_PATH));
?>