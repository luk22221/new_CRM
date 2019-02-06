<?php
session_start();
if (!isset($_SESSION['zalogowany']) || !isset($_GET['id'])){
	header("location: ../index.php");
}
include "../config/connect.php";

$id = ($_GET['id']);
if ($result = $connect->query("DELETE FROM clients  WHERE id='$id'")) {
	$_SESSION['client_delete'] = "Usunięto uzytkonika";
	header("location: ../clients.php");
}