<?php
	//włącz sesje
	session_start();
	//zniszcz sesje
	session_unset();
	//przekierowanie
	header('Location: ../index.php');

?>