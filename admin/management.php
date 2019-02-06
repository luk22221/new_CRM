<?php
session_start();
if (!isset($_SESSION['zalogowany']) || ($_SESSION['privilege'] != 1)){
	header("location: ../index.php");
}
include "../config/connect.php";
include "../include/nav.php";
?>




<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM - panel administratora</title>
	<link rel="stylesheet" href="../css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div style='margin-left: 20%; margin-right: 20%; width: 60%;'>
<?php
if (isset($_SESSION['user_delete'])){
	echo '<div class="succes">'."usunięto użytkownika".'</div>';
	unset($_SESSION['user_delete']);
}?>
	<form action="add_user.php" method="post">
		<input type="submit" name="submit" value="dodaj nowego użytkownika" />
		<br />
	</form>
<?php
if ($result = $connect->query("SELECT *FROM users ")) {
	$records_per_page = 6;
	if(isset($result) && ($result->num_rows != 0)){
	    $total_records = $result->num_rows;
	    $total_pages = ceil($total_records / $records_per_page);

	    if(isset($_GET['page']) && is_numeric($_GET['page'])){

	        $show_page = $_GET['page'];

	        if($show_page > 0 && $show_page <= $total_pages){
	            $start = ($show_page-1) * $records_per_page;
	            $end = $start + $records_per_page;
	        } else {
	            $start = 0;
	            $end = $records_per_page;
	        }
	    } else {
	        $start = 0;
	        $end = $records_per_page;
	    }
	    echo "<p> Zobacz stronę: ";
	    for($i = 1; $i <= $total_pages; $i++){
	        if(isset($_GET['page']) && $_GET['page'] == $i){
	            echo $i . " ";
	        } else {
	            echo "<a href='management.php?page=$i'>" . $i . "</a>";
	        }
	    }
	    echo "</p>";
	    echo "<table class='table_management table table-hover table-bordered' style=''>";
	    echo "<tr>";
	    echo "<td>" . "id";
	    echo "<td>" . "nazwa";
	    echo "<td>" . "email";
	    echo "<td>" . "data dodania";
	    echo "<td>" . "Usuń";
	    echo "<tr>";
	    for($i = $start; $i < $end; $i++){
	        if($i == $total_records) {break;}

	        $result->data_seek($i);
	        $row = mysqli_fetch_array($result);

	        echo "<tr>";
	        echo "<td>" . $row['id'];
	        echo "<td>" . $row['user'];
	        echo "<td>" . $row['email'];
	        echo "<td>" . $row['datetime'];
	        echo "<td>" . "<a href='delete_user.php?id=" . $row['id'] . "'>"?><button type="button">Usuń</button></a><br /><?php
	        echo "<tr>";

	    }
	    echo "<table>";
	    $result->free_result();
	    $connect->close();



	} else {
	    echo '<div class="error">'."brak rekordów".'</div>';
	}
} else {
	echo '<div class="error">'."brak rekordów".'</div>';
}

?></div><?php
include "../include/footer.php";
?>

</body>
</html>