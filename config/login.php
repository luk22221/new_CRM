<?php

session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
{
	header('Location: ../index.php');
	exit();
}

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
	$connect = new mysqli($host, $db_user, $db_password, $db_name);

	if ($connect->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
		$login = $connect->real_escape_string($_POST['login']);
		$haslo = $connect->real_escape_string($_POST['haslo']);

		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	
		if ($rezultat = $connect->query(
		sprintf("SELECT * FROM users WHERE user='%s'",
		mysqli_real_escape_string($connect,$login))))
		{

			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{

				$wiersz = $rezultat->fetch_assoc();
				if(password_verify($haslo, $wiersz['pass']))
					{
					$_SESSION['zalogowany'] = true;
					$_SESSION['id'] = $wiersz['id'];
					$_SESSION['user'] = $wiersz['user'];
					$_SESSION['pass'] = $wiersz['pass'];
					$_SESSION['privilege'] = $wiersz['privilege'];

					unset($_SESSION['blad']);
					$rezultat->free_result();
					header('Location: ../index.php');
				}else {
				
					$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: ../index.php');
				
			}
				
			} else {
				
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: ../index.php');
				
			}
			
		}else{
			throw new Exception($connect->error);
		}
		
		$connect->close();
	}
}
catch(Exception $e)
{
		?><div class="error">Nie można sie zalogować spróbuj później</div>
		<?php
	//echo '<br />Informacja developerska: '.$e;
}
	
?>