<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: /include/login_page.php');

include "config/connect.php";
include "include/nav.php";

$id = $_SESSION['id'];
if(isset($_SESSION['account_data']))
{ 
	echo($_SESSION['account_data']);
	unset($_SESSION['account_data']);
}
if(isset($_SESSION['account_pass']))
{ 
	echo($_SESSION['account_pass']);
	unset($_SESSION['account_pass']);
}
?>



<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM</title>
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div style="margin-right: 30%; margin-left: 30%;">
<?php
$id = $_SESSION['id'];
if($result = $connect->query("SELECT pass,user,email,phone,avatar_path FROM users where id='$id'")){
	$data_account = mysqli_fetch_array($result);

$_SESSION['fr_user']= $data_account['user'];
$_SESSION['fr_phone']= $data_account['phone'];
$_SESSION['fr_email']= $data_account['email'];
//$_SESSION['fr_avatar_path']= $data_account['avatar_path'];
}

?>
<form class="form-group" enctype="multipart/form-data" action="save_in_database/account_settings_save.php" method="post">
<div class="form-group">
	<label for="">Nazwa użytkownika: </label> 
	<br /> <input class="form-control" type="text" name="user" value="<?php
	if (isset($_SESSION['fr_user']))
		{
			echo $_SESSION['fr_user'];
			unset($_SESSION['fr_user']);
		}?>">
	<?php 
	if (isset($_SESSION['e_user']))
		{
			echo '<div class="error">'.$_SESSION['e_user'].'</div>';
			unset($_SESSION['e_user']);
		}?>

</div>
<div class="form-group">
	<label for="">Numer telefonu: </label>
	<input class="form-control" type="tel" name="phone" pattern=".*[0-9]{3}.?[0-9]{3}.?[0-9]{3}" value="<?php
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
	<label for="">Email: </label>
	<input class="form-control" type="email" value="<?php
		if (isset($_SESSION['fr_email']))
		{
			echo $_SESSION['fr_email'];
			unset($_SESSION['fr_email']);
		}
	?>" name="email" /><br />
	
	<?php
	if (isset($_SESSION['e_email']))
		{
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
	?>
</div>
	<input class="btn btn-primary" type="submit" value="Aktualizuj dane" name="submit1"/>
</form>

<form  action="save_in_database/account_settings_save.php" method="post">
<div class="form-group">
	<label for="">Stare hasło: </label> 
	<input class="form-control" type="text" name="old_pass" />
<?php
	if (isset($_SESSION['e_pass1']))
	{
		echo '<div class="error">'.$_SESSION['e_pass1'].'</div>';
		unset($_SESSION['e_pass1']);
	}?>
</div>
<div class="form-group">
	<label for="">Nowe hasło: </label>  
	<input class="form-control" type="password" name="new_pass1" />

</div>
<div class="form-group">
	<label for="">Powtórz nowe hasło: </label>
	<input class="form-control" type="password" name="new_pass2" />
<?php
	if (isset($_SESSION['e_pass2']))
	{
		echo '<div class="error">'.$_SESSION['e_pass2'].'</div>';
		unset($_SESSION['e_pass2']);
	}?>
</div>
	<input class="btn btn-primary" type="submit" value="Zmień hasło" name="submit2"/>

</form>

</div>
<?php
include "include/footer.php";
?>
</body>
</html>