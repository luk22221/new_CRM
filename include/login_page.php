<?php
include "../config/connect.php";
session_start();
if (isset($_SESSION['zalogowany'])) header('Location: ../index.php');
?>

<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM-logowanie</title>
	<link rel="stylesheet" href="../css/style_login_page.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body style="background-image: url(../img/office1.png);  background-repeat: no-repeat;  background-position: center; height: 100%; width: : 100%; text-align: center; background-color : grey">	
<form action="../config/login.php" style="margin-top: 220px; display: inline-block;" method="post">

<span style="font-size: 16px;">Login: </span><br /> <input type="text" name="login" class="form-control" />
<span style="font-size: 16px;">Hasło: </span><br /> <input type="password" name="haslo" class="form-control" /> <br />
<input type="submit" value="zaloguj się" class="btn btn-primary" />
</form>
<br /><br />

<?php
//wyswietlanie błędu w przypadku niepoprawnych danych logowania
if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
unset($_SESSION['blad']);

?>
</body>	
</html>		
