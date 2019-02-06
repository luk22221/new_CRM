<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: ../include/login_page.php');

include "../config/connect.php";

if (isset($_POST['Submit'])){
    $_SESSION['flag'] = 1;


    $result = $connect->query('SELECT * FROM clients WHERE name like "%" and last_name like "%" and email like "%" and phone like "%" and town like"%"');
    $_SESSION['find_name'] = $_POST['find_name'];
    $_SESSION['find_last_name'] = $_POST['find_last_name'];
    $_SESSION['find_phone'] = $_POST['find_phone'];
    $_SESSION['find_email'] = $_POST['find_email'];
    $_SESSION['find_town'] = $_POST['find_town'];
    if (empty($_SESSION['find_name'])) {
    	$_SESSION['find_name'] = "%";
    }
    if (empty($_SESSION['find_last_name'])) {
    	$_SESSION['find_last_name'] = "%";
    }
    if (empty($_SESSION['find_phone'])) {
    	$_SESSION['find_phone'] = "%";
    }
    if (empty($_SESSION['find_email'])) {
    	$_SESSION['find_email'] = "%";
    }
    if (empty($_SESSION['find_town'])) {
    	$_SESSION['find_town'] = "%";
    }

    header('Location: ../clients.php');
}

