<?php
session_start();
if (!isset($_SESSION['zalogowany']) || ($_SESSION['privilege'] != 1) || !isset($_GET['id'])){
	header("location: ../index.php");
}
include "../config/connect.php";

$id = ($_GET['id']);
if ($result = $connect->query("DELETE FROM users  WHERE id='$id'")) {
	$_SESSION['user_delete'] = "Usunięto uzytkonika";
	header("location: management.php");
}