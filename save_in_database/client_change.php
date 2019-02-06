<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: ../include/login_page.php');

include "../config/connect.php";


if (isset($_POST['last_name']))
{
	//Udana walidacja? Załóżmy, że tak!
	$wszystko_OK=true;
	
	//przypisanie do zmiennych wpisanych informacji
	$name = $_POST['name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$postal_code = $_POST['postal_code'];
	$town = $_POST['town'];
	$street = $_POST['street'];
	$phone = $_POST['phone'];
	$description = $_POST['description'];
	
	//Sprawdzenie długości imienia i nazwiska
	if ((strlen($name)<1) || (strlen($name)>25))
	{
		$wszystko_OK=false;
		$_SESSION['e_name']="Imie musi posiadać od 1 do 25 znaków!";
	}
	if ((strlen($last_name)<1) || (strlen($last_name)>25))
	{
		$wszystko_OK=false;
		$_SESSION['e_last_name']="Nazwisko musi posiadać od 1 do 25 znaków!";
	}
	//Czy numer to wartośc liczbowa
	if(!(is_numeric($_POST['phone'])) && ($_POST['phone']) != null){
		$wszystko_OK=false;
		$_SESSION['e_phone']="Podaj poprawny numer telefonu!";
	}
	
	
	// Sprawdź poprawność adresu email
	$email = $_POST['email'];
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		if (!empty($email))
		{
		
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
	}
	

	//Zapamiętaj wprowadzone dane
	$_SESSION['fr_name'] = $name;
	$_SESSION['fr_last_name'] = $last_name;
	$_SESSION['fr_email'] = $email;
	$_SESSION['fr_phone'] = $phone;
	$_SESSION['fr_postal_code'] = $postal_code;
	$_SESSION['fr_town'] = $town;
	$_SESSION['fr_street'] = $street;
	$_SESSION['fr_description'] = $description;

	


	if ($wszystko_OK==true)
	{
		$id = $_SESSION['fr_id'];
		
		$stmt = $connect->prepare("UPDATE clients set name=? , last_name=? , email=? , phone=? , postal_code=? , town=? , street=? , description=? WHERE id=?");
		$stmt->bind_param("sssissssi", $name, $last_name, $email, $phone, $postal_code, $town, $street, $description, $id );
		if ($stmt->execute()) {

			$_SESSION['client_changed']=true;
			header('Location: ../clients.php');
		}
		else
		{
			throw new Exception($connect->error);
			header('Location: ../clients_info.php?id=" . $id . "');
		}
		
	} else {
		header('Location: ../clients_info.php?id=" . $id . "');
	}
	
	$connect->close();
	
} else {
	header('Location: ../clients_info.php?id=" . $id . "');
}