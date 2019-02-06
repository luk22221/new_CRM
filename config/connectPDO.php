<?php

	$host = "localhost";
	$db_user = "root";
	$db_password = "";
	$db_name = "crm";
	//$connect2 = new PDO($host; $db_name; charset=utf8, $db_user, $db_password);
	//$connect2->set_charset("utf8");
	$connect2 = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $db_user, $db_password, [
	PDO::ATTR_EMULATE_PREPARES => false, 
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

?>