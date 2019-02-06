<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "../config/connectPDO.php";


if (isset($_POST['task_importance']))
{
	$task_name = $_POST['task_name'];
	$type = $_POST['task_type'];
	$importance = $_POST['task_importance'];
	if (isset($_POST['client_name'])) {
		$name = $_POST['client_name'];
	}
	if (isset($_POST['client_last_name'])) {
		$last_name = $_POST['client_last_name'];
	}

	if (isset($_POST['task_description'])) {
		$description = $_POST['task_description'];
	}	
	if (isset($_POST['task_completion_date'])) {
		$date = $_POST['task_completion_date'] . " ";
	} else{
		$date = "0000-00-00 ";
	}
	if (isset($_POST['task_completion_time'])) {
		$time = $_POST['task_completion_time'];
	} else{
		$time = "00:00";
	}
	$date_planing_end = $date . $time;
	$id_creator = $_SESSION['id'];
	if (isset($_POST['users_added'])) {
		$users = $_POST['users_added'];
		//$users += ["creator" => $id_creator];
	} else {
		$users = array();
		//$users += ["creator" => $id_creator];
	}



	if (isset($name) && isset($last_name)) {
		$stmt = $connect2->prepare("SELECT id from clients where name=? and last_name=?");
		$stmt->bindParam(1, $name, PDO::PARAM_STR);
		$stmt->bindParam(2, $last_name, PDO::PARAM_STR);
		$stmt->execute();
		$count = $stmt->rowCount();
		$stmt = null;
		if ($count == 0) {
			$stmt = $connect2->prepare("INSERT INTO clients(name, last_name) VALUES(?, ?)");
			$stmt->bindParam(1, $name, PDO::PARAM_STR);
			$stmt->bindParam(2, $last_name, PDO::PARAM_STR);
			$stmt->execute();
			$stmt = null;
		}
	}
	$stmt = $connect2->prepare("SELECT id from clients where name=? and last_name=?");
	$stmt->bindParam(1, $name, PDO::PARAM_STR);
	$stmt->bindParam(2, $last_name, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$client_id = ($result['id']);
	if (empty($client_id)) {
		$client_id = 0;
	}
	$stmt = null;


	function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	$real_name = generateRandomString();
	$insert = $connect2->prepare("INSERT INTO tasks(id_t, name, description_task, id_client, start_date, date_planing_end, type_task, importance,real_name, done) VALUES (NULL,?,?,?, NOW(),?,?,?,?,0)");
	$insert->bindParam(1, $task_name, PDO::PARAM_STR);
	$insert->bindParam(2, $description, PDO::PARAM_STR);
	$insert->bindParam(3, $client_id, PDO::PARAM_INT);
	$insert->bindParam(4, $date_planing_end, PDO::PARAM_STR);
	$insert->bindParam(5, $type, PDO::PARAM_STR);
	$insert->bindParam(6, $importance, PDO::PARAM_STR);
	$insert->bindParam(7, $real_name, PDO::PARAM_STR);
	if ($insert->execute()) {
		$insert = null;
		$stmt2 = $connect2->prepare("SELECT id_t from tasks where real_name = ? ORDER BY id_t DESC");
		$stmt2->bindParam(1, $real_name, PDO::PARAM_STR);
		$stmt2->execute();
		$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$id_task = ($result2['id_t']);
		$stmt2 = null;


			
		foreach ($users as $user) {
			$stmt3 = $connect2->prepare("INSERT INTO relation_tasks_users VALUES(?,?)");
			$stmt3->bindParam(1, $user, PDO::PARAM_INT);
			$stmt3->bindParam(2, $id_task, PDO::PARAM_INT);
			$stmt3->execute();
			$stmt3 = null;
		}
		
		$_SESSION['info_task'] = "Udało się dodać nowe zadanie";
		header('Location: ../index.php');

	} else {
		$_SESSION['info_task'] = "Nie udało się dodać nowego zadania";
		header('Location: ../new_task.php');
	}




} else {
	$_SESSION['info_task'] = "Nie udało się dodać nowego zadania";
	header('Location: ../new_task.php');
}