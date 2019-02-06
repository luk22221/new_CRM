<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: /include/login_page.php');

include "config/connectPDO.php";
include "include/nav.php";

?>

<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM</title>
	<link rel="stylesheet" href="/css/style.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/js/jquery.redirect.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
<?php
if (isset($_SESSION['info_task']))
{
	echo '<h3 style="text-align: center;"><div class="succes">' . $_SESSION['info_task'] . '</div></h3>';
	unset($_SESSION['info_task']);
}
?>

<?php
$id_creator = $_SESSION['id'];
$stmt = $connect2->prepare("SELECT tasks.id_t, tasks.importance,  relation_tasks_users.id_user,tasks.name, tasks.date_planing_end, users.user FROM tasks LEFT JOIN relation_tasks_users ON tasks.id_t=relation_tasks_users.id_task LEFT JOIN users ON users.id=relation_tasks_users.id_user WHERE relation_tasks_users.id_user=? AND tasks.done=0 order by tasks.date_planing_end ASC");
$stmt->bindParam(1, $id_creator, PDO::PARAM_STR);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{	
	?><div class="tasks"><?php
	echo 'Nazwa zadania: ' . $row['name'] . '<br />';
	echo "Czas do końca zadania: " .  $row['date_planing_end']  . '<br />';
	echo 'Stopień ważności: ';
	if ($row['importance'] == "ważne") {
		echo "<span style='color:red'>" . "Ważne" . '</span><br />';
	} else if ($row['importance'] == "średnio ważne"){
		echo "<span style='color:yellow'>" . "średnio ważne" . '</span><br />';
	} else if ($row['importance'] == "mało ważne"){
		echo "<span style='color:green'>" . "mało ważne" . '</span><br />';
	} else {
		echo "niezdefiniowano" . '<br />'; 	
	}
	$id_task = $row['id_t'];

	echo '<button type="submit" class="task_done_class button" id="task_done_' . $row["id_t"] . '" onclick="">Zaznacz jako wykonane</button>';

	echo "<td>" . "<a href='tasks_info.php?id=" . $row['id_t'] . "'>"?><button class="button" style="color:black;" type="button">Szczegóły i edycja</button></a><?php ;

 	echo '<br />';
 	?></div><?php
}
$stmt = null;
?></div><?php
include "include/footer.php";
?>


<script>
	$(document).on('click', '.task_done_class', function() {
		let buttonId = $(this).attr('id');
		let button = this;
		
		let data = {
			id: buttonId.replace('task_done_', '')
		};


		data = JSON.stringify(data);

		$.ajax({
			    url: '/save_in_database/task_done.php',
			    type: 'POST',
			    contentType: "application/json",
			    dataType: "text",
			    data: data,
			    success: function(r) {
			    	console.log(r);
					$(button).css('color', 'green');
					$(button).attr("disabled", "disabled");
			    },
			    error: function(xhr, status, error) {
			    	console.log(this.data);
					alert(error);
				}
			});
	});
</script>
</body>
</html>

