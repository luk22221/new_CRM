<?php
include "../config/connect.php";
session_start();
if(isset($_POST['submit']) && ($_SESSION['zalogowany'])== 1 && ($_SESSION['privilege'] == 1)){

function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$user = generateRandomString();
$pass = generateRandomString();?>

<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM-add user</title>
	<link rel="stylesheet" href="../css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<?php
include "../include/nav.php";
try 
{
	$connect = new mysqli($host, $db_user, $db_password, $db_name);
	if ($connect->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{	
		
		//is nick already in database?
		$resultnick = $connect->query("SELECT id FROM users WHERE user='$user'");
		if (!$resultnick) throw new Exception($connect->error);
		$how_much_users = $resultnick->num_rows;

		for ($i=0 ; $how_much_users>0 ; $user = generateRandomString(8) ) { 
			$resultnick = $connect->query("SELECT id FROM users WHERE user='$user'");
			$how_much_users = $resultnick->num_rows;
		}
		
		$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
		if ($connect->query("INSERT INTO users (id, user, pass,  email, datetime,privilege) VALUES (NULL, '$user', '$pass_hash', '0',NOW(),'0' );"))
		{
			echo "<h2 style='text-align: center;'>" . "Nowy użytkownik został dodany jego dane do logowania są następujące:" . "</h2>" . '<br/>';
		}
		else
		{
			throw new Exception($connect->error);
		}
			
		
		$connect->close();
	}
	
}
catch(Exception $e)
{
	?><h2 style='text-align: center;' class='error'>Błąd serwera dodaj użytkownika w innym terminie</h2><?php
	$pass = " ";
	$user = " ";

}
echo "<h3><span style='margin-left: 40%;text-align: center;'>login: " . ($user) . '</span></h3>';
echo "<h3><span style='margin-left: 40%;text-align: center;'>hasło: " . ($pass) . '</span></h3>';
echo "<span style='margin-left: 40%;text-align: center;'><a href='management.php'>Powrót</a></span>";




}else{
	echo "musisz zalogować sie jako administrator";
}

?>
</body>
</html>

