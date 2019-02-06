<?php

	$host = "localhost";
	$db_user = "root";
	$db_password = "";
	$db_name = "crm";
	$connect = new mysqli($host, $db_user, $db_password, $db_name);
	$connect->set_charset("utf8");
	//$connect->query("SET NAMES 'utf8'");
	//$connect2 = new PDO($host; $db_name; charset=utf8, $db_user, $db_password);
	//$connect2->set_charset("utf8");

?>

