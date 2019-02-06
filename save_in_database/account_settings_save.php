<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: ../include/login_page.php');

include "../config/connect.php";

$id = $_SESSION['id'];

if (isset($_POST['submit1']) && ($_POST['submit1']=="Aktualizuj dane"))

{	
	$everything_OK_data=true;
	$user = mysqli_real_escape_string($connect,$_POST['user']);
	$phone = mysqli_real_escape_string($connect,$_POST['phone']);

	

	$result = $connect->query("Select id FROM users WHERE user='$user'");			
	$How_many_users = $result->num_rows;
	$result->free_result();
	if ($_SESSION['user']!=$user) {
		$result = $connect->query("Select id FROM users WHERE user='$user'");			
		$How_many_users = $result->num_rows;
		$result->free_result();
		if($How_many_users>0)
		{
			$everything_OK_data=false;
			$_SESSION['e_user']="Istnieje już użytkownik o takiej nazwie!";
		}
	}
	$_SESSION['usres']= $How_many_users;

	if ((strlen($user)<2) || (strlen($user)>20))
	{
		$everything_OK_data=false;
		$_SESSION['e_user']="Nazwa użytkownika musi zawierać od 2 do 20 znaków";
	}

	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		if (!empty($email))
		{
		
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
	}

	if ($everything_OK_data==true)
	{
		$stmt = $connect->prepare("UPDATE users set user=?, phone=?, email=? where id=?");
		$stmt->bind_param("sisi", $user, $phone, $email,$id );
		if ($stmt->execute()) {
			$_SESSION['account_data']= "Zmieniono dane konta";
			header('Location: ../account_settings.php');
		}else{
			$_SESSION['account_data']= "Nie udało się zmienić danych";
			header('Location: ../account_settings.php');
		}
	} else {
		header('Location: ../account_settings.php');
	}
} else {

	header('Location: ../account_settings.php');
}
if (isset($_POST['submit2']) && ($_POST['submit2']=="Zmień hasło"))
{
	$everything_OK_pass=true;
	//$pass1 = mysqli_real_escape_string($connect,$_POST['pass1']);
	//$pass2 = mysqli_real_escape_string($connect,$_POST['pass2']);
	$old_pass = mysqli_real_escape_string($connect,$_POST['old_pass']);
	$pass1 = $_POST['new_pass1'];
	$pass2 = $_POST['new_pass2'];
	// $old_pass = $_POST['old_pass'];
	if (strlen($old_pass)==0) {
		$everything_OK_pass=false;
		$_SESSION['e_pass1']="Wprowadź aktualne hasło";
	}
	if ((ctype_alnum($pass1)==false) || (ctype_alnum($pass2)==false))
	{
		$everything_OK_pass=false;
		$_SESSION['e_pass2']="Nowe hasło może składać się tylko z liter i cyfr (bez polskich znaków)";
	}
	if ((strlen($pass1)<8) || (strlen($pass1)>20))
	{
		$everything_OK_pass=false;
		$_SESSION['e_pass2']="Nowe hasło musi posiadać od 8 do 20 znaków!";
	}
	
	if ($pass1!=$pass2)
	{
		$everything_OK_pass=false;
		$_SESSION['e_pass2']="Podane hasła nie są identyczne!";
	}
	if ($everything_OK_pass==true)
	{

		if($result = $connect->query("SELECT pass FROM users where id='$id'"))
		{
			$old_pass_array = $result->fetch_assoc();
			$old_passs = $old_pass_array['pass'];
			$result->free_result();
			if(password_verify($old_pass, $old_passs))
			{
				$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
				$stmt = $connect->prepare("Update users set pass=? where id=?");
				$stmt->bind_param("si", $pass_hash, $id );
				if ($stmt->execute()) {
					$_SESSION['account_pass']= "Zmieniono hasło na nowe";
					header('Location: ../account_settings.php');
				}else{
					$_SESSION['account_pass']= "Nie udało się zmienić hasła";
					header('Location: ../account_settings.php');
				}
			} else {
				$_SESSION['e_pass1']= "Podano nie prawidłowe hasło";
				header('Location: ../account_settings.php');
			}
		
		} else{
			$_SESSION['account_pass']= "Nie udało się połączyć z bazą";
			header('Location: ../account_settings.php');
		}
	}
} else {
	header('Location: ../account_settings.php');
}

?>