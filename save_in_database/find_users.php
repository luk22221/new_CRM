<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "../config/connectPDO.php";
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $connect2->prepare("SELECT relation_tasks_users.id_user, users.user FROM relation_tasks_users LEFT JOIN tasks ON tasks.id_t=relation_tasks_users.id_task LEFT JOIN users ON users.id=relation_tasks_users.id_user WHERE tasks.id_t=?");
$stmt->bindParam(1, $id, PDO::PARAM_STR);
if ($stmt->execute()){
	$users =array();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
    array_push($users, trim($row['user']). '###' . trim($row['id_user']));
    $count = $stmt->rowCount();
    for ($i = 1; $i < $count; $i++) { 
		$targetRow = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT,$i);
		array_push($users, trim($targetRow['user']). '###' . trim($targetRow['id_user'])); 
	}
}
$stmt = null;

echo json_encode($users);

