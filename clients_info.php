<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: /include/login_page.php');

include "config/connect.php";
include "include/nav.php";

if(isset($_GET['id'])){	
	if(is_numeric($_GET['id'])){
		$id = ($_GET['id']);
		if ($result = $connect->query("SELECT * FROM clients  WHERE id='$id';")){
			$row = mysqli_fetch_array($result);
            $_SESSION['fr_id'] = $row['id'];
            $_SESSION['fr_name'] = $row['name'];
			$_SESSION['fr_last_name'] = $row['last_name'];
			$_SESSION['fr_email'] = $row['email'];
			$_SESSION['fr_phone'] = $row['phone'];
			$_SESSION['fr_postal_code'] = $row['postal_code'];
			$_SESSION['fr_town'] = $row['town'];
			$_SESSION['fr_street'] = $row['street'];
			$_SESSION['fr_description'] = $row['description'];



        } else{
        	echo("Błąd bazy danych");
        }

	}

}
?>
<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM-Edytuj klienta</title>
	<link rel="Stylesheet" type="text/css" href="css/style.css." />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div style="margin-right: 22%; margin-left: 22%;">
<form method="post" action="/save_in_database/client_change.php">
	<h3 style="text-align: center;">Edytuj dane klienta</h3>
	<div class="form-group">	
		<label for="">Imie: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_name']))
			{
				echo $_SESSION['fr_name'];
				unset($_SESSION['fr_name']);
			}
		?>" name="name" />
			
		<?php
			if (isset($_SESSION['e_name']))
			{
				echo '<div class="error">'.$_SESSION['e_name'].'</div>';
				unset($_SESSION['e_name']);
			}
		?>

	</div>
	<div class="form-group">
		<label for="">nazwisko: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_last_name']))
			{
				echo $_SESSION['fr_last_name'];
				unset($_SESSION['fr_last_name']);
			}
		?>" name="last_name" />
		
		<?php
			if (isset($_SESSION['e_last_name']))
			{
				echo '<div class="error">'.$_SESSION['e_last_name'].'</div>';
				unset($_SESSION['e_last_name']);
			}
		?>

 	</div>
	<div class="form-group">
		<label for="">Numer telefonu: </label> 
		<input class="form-control" type="text" name="phone" value="<?php
		if (isset($_SESSION['fr_phone']))
			{
				echo $_SESSION['fr_phone'];
				unset($_SESSION['fr_phone']);
			}?>">
		<?php 
		if (isset($_SESSION['e_phone']))
			{
				echo '<div class="error">'.$_SESSION['e_phone'].'</div>';
				unset($_SESSION['e_phone']);
			}?>
		
	</div>
	<div class="form-group">
		<label for="">E-mail: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_email']))
			{
				echo $_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>" name="email" />
		
		<?php
		if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?>

	</div>
	<div class="form-group">
		<label for="">Kod pocztowy: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_postal_code']))
			{
				echo $_SESSION['fr_postal_code'];
				unset($_SESSION['fr_postal_code']);
			}
		?>" name="postal_code" />
		
		<?php
			if (isset($_SESSION['e_postal_code']))
			{
				echo '<div class="error">'.$_SESSION['e_postal_code'].'</div>';
				unset($_SESSION['e_postal_code']);
			}
		?>

	</div>
	<div class="form-group">
		<label for="">Miasto:</label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_town']))
			{
				echo $_SESSION['fr_town'];
				unset($_SESSION['fr_town']);
			}
		?>" name="town" />
		
		<?php
			if (isset($_SESSION['e_town']))
			{
				echo '<div class="error">'.$_SESSION['e_town'].'</div>';
				unset($_SESSION['e_town']);
			}
		?>

	</div>
	<div class="form-group">
		<label for="">Ulica: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_street']))
			{
				echo $_SESSION['fr_street'];
				unset($_SESSION['fr_street']);
			}
		?>" name="street" />
		
		<?php
			if (isset($_SESSION['e_street']))
			{
				echo '<div class="error">'.$_SESSION['e_street'].'</div>';
				unset($_SESSION['e_street']);
			}
		?>
	</div>
	<div class="form-group">
		<label for="">Opis klienta: </label> 
		<input class="form-control" type="text" value="<?php
			if (isset($_SESSION['fr_description']))
			{
				echo $_SESSION['fr_description'];
				unset($_SESSION['fr_description']);
			}
		?>" name="description" />
		
		<?php
			if (isset($_SESSION['e_description']))
			{
				echo '<div class="error">'.$_SESSION['e_description'].'</div>';
				unset($_SESSION['e_description']);
			}
		?>
</div>

		
		<input class="btn btn-primary" type="submit" value="Zmień dane klienta" />
		
	</form>
<a href="clients.php">powrót</a> 

</div>
<?php
include "include/footer.php";
?>
</body>
</html>
