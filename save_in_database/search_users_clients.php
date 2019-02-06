<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "../config/connect.php";
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['first_name']) && isset($data['last_name'])) {
	$first_name = trim(strtolower($data['first_name']));
	$last_name = trim(strtolower($data['last_name']));
	$result = $connect->query("SELECT name,last_name FROM clients WHERE LOWER(name) LIKE '%$first_name%' AND LOWER(last_name) LIKE '%$last_name%'");
	$total_records = $result->num_rows;
	$found = array();
	for($i = 0; $i < $total_records; $i++){
	    $result->data_seek($i);
	    $row = mysqli_fetch_array($result);
	    array_push($found, $row['name']." ".$row['last_name']);
	}
	echo json_encode($found);
} else if (isset($data['user'])) {
	$user = trim(strtolower($data['user']));
	$result = $connect->query("SELECT user,id FROM users WHERE LOWER(user) LIKE '%$user%'");
	$total_records = $result->num_rows;
	$found = array();
	for($i = 0; $i < $total_records; $i++){
	    $result->data_seek($i);
	    $row = mysqli_fetch_array($result);
	    array_push($found, $row['user']."#".$row['id']);
	}
	$result->free_result();
	echo json_encode($found);
} 