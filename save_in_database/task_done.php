<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "../config/connectPDO.php";
$data = json_decode(file_get_contents('php://input'), true);
echo("ID: " . $data['id'] . "\n");
$stmt = $connect2->prepare("UPDATE tasks set done=1 WHERE id_t=?");
$stmt->bindParam(1, $data['id'], PDO::PARAM_INT);
$stmt->debugDumpParams();
$stmt->execute();
?>