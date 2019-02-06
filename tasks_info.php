<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "config/connectPDO.php";
	?>
<html lang="pl">
<html>
<head>
	<meta charset="UTF-8 general ci">
	<title>CRM-edycja zadania</title>
	<link rel="stylesheet" href="/css/style.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/js/jquery.redirect.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		#user_list {
			background-color: lightblue;
		}

		#added_users_list {
			background-color: lightgreen;
		}
	</style>
</head>
<body>

<?php
include "include/nav.php";
if(isset($_GET['id'])){	
	if(is_numeric($_GET['id'])){
		$id = ($_GET['id']);
		$stmt = $connect2->prepare("SELECT * FROM tasks LEFT JOIN relation_tasks_users ON tasks.id_t=relation_tasks_users.id_task LEFT JOIN users ON users.id=relation_tasks_users.id_user WHERE tasks.id_t=?");
			$stmt->bindParam(1, $id, PDO::PARAM_STR);
		if ($stmt->execute()){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['task_name'] = $row['name'];
            $_SESSION['type_task'] = $row['type_task'];
            $_SESSION['importance'] = $row['importance'];
            $_SESSION['start_date'] = $row['start_date'];
            $_SESSION['date/time_planing_end'] = $row['date_planing_end'];
            $_SESSION['description_task'] = $row['description_task'];
            $id_client = $row['id_client'];

			$stmt = null;

			$stmt = $connect2->prepare("SELECT name, last_name FROM clients WHERE id=?");
			$stmt->bindParam(1, $id_client, PDO::PARAM_STR);
			$stmt->execute();
			$row2 = $stmt->fetch(PDO::FETCH_ASSOC);
			if (isset($row2['name'])) {
				$_SESSION['name'] = $row2['name'];
			}
			if (isset($row2['last_name'])) {
				$_SESSION['last_name'] = $row2['last_name'];
			}
			$stmt = null;
        } else{
        	echo("Błąd bazy danych");
        }

	}

}
$_SESSION['date_planing_end'] = substr($_SESSION['date/time_planing_end'], 0, 10);
$_SESSION['time_planing_end'] = substr($_SESSION['date/time_planing_end'], 11, 5);
?>
<div style="margin-right: 20%; margin-left: 20%;">
<form class="form-group" action="save_in_database/task_update.php" method="post">
<h3 style="text-align: center;">Szczegóły zadania</h3>

	<input type="hidden" id="task_update_input" name="task_id_input" value="<?php echo ($id); ?>">
<div class="form-group">			
	<label for="">Nazwa zadania </label>
	<input class="form-control" id="task_name_input" type="text" value="<?php
			if (isset($_SESSION['task_name']))
			{
				echo $_SESSION['task_name'];
				unset($_SESSION['task_name']);
			}
		?>" name="name" />
</div>
<div class="form-group">
	
	<label for="">Rodzaj zadania</label>
	<select class="form-control" id="task_type_input" name="type_task">
		<option><?php
			if (isset($_SESSION['type_task']))
			{
				echo $_SESSION['type_task'];
				unset($_SESSION['type_task']);
			}
		?></option>
		<option>inny</option>
		<option>Standardowy projekt strony internetowej</option>
		<option>Telefon</option>
		<option>Spotkanie</option>
	</select>
</div>
<div class="form-group">
	<label for="">Stopień ważności zadania</label>
	<select class="form-control" id="task_importance_input" name="importance">
		<option><?php
			if (isset($_SESSION['importance']))
			{
				echo $_SESSION['importance'];
				unset($_SESSION['importance']);
			}
		?></option>
		<option>inny</option>
		<option>ważne</option>
		<option>średnio ważne</option>
		<option>mało ważne</option>
	</select>
</div>
<div class="form-group">
	<label for="">klient</label>
	<input class="form-control" type="text" id="find_name" value="<?php
			if (isset($_SESSION['name']))
			{
				echo $_SESSION['name'];
				unset($_SESSION['name']);
			}
		?>" name="first_name" />
	<input class="form-control" type="text" id="find_last_name" value="<?php
			if (isset($_SESSION['last_name']))
			{
				echo $_SESSION['last_name'];
				unset($_SESSION['last_name']);
			}
		?>" name="last_name" />
	<div id="client_list">
	</div>
</div>
<div class="form-group">


	<label for="">Uzytkownicy podpięci do zadania</label>
	<input type="text" id="users_hooked" value="" name="" />
	<div id="user_list"></div>
	<div id="added_users_list" name="added_users_list"></div>

</div>
<div class="form-group">
	<label for="">Opis zadania</label>
	<input class="form-control" id="task_description_input" type="text" value="<?php
			if (isset($_SESSION['description_task']))
			{
				echo $_SESSION['description_task'];
				unset($_SESSION['description_task']);
			}
		?>" name="description_task" />
		
</div>
<div class="form-group">
	<label for="">Planowana data wykonania zadania</label>
	<input class="form-control" id="task_completion_date_input" type="date"  value="<?php
			if (isset($_SESSION['date_planing_end']))
			{
				echo $_SESSION['date_planing_end'];
				unset($_SESSION['date_planing_end']);
			}
		?>" name="date"  />
	<input class="form-control" id="task_completion_time_input" type="time"  value="<?php
			if (isset($_SESSION['time_planing_end']))
			{
				echo $_SESSION['time_planing_end'];
				unset($_SESSION['time_planing_end']);
			}
		?>" name="time" value="12:00" />
</div>
	
	<button class="btn btn-primary" type="submit" id="nowe-zadanie-btn" onclick="return false;">Zapisz zmiany</button>
	
</form>

<?php
include "include/footer.php";
?>

<script>
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
		    sURLVariables = sPageURL.split('&'),
		    sParameterName,
		    i;

		for (i = 0; i < sURLVariables.length; i++) {
		    sParameterName = sURLVariables[i].split('=');

		    if (sParameterName[0] === sParam) {
		        return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		    }
		}
	};

	var wto;
	var response = [];
	var users_added = {};

	$.ajax({
	    url: '/save_in_database/find_users.php',
	    type: 'POST',
	    contentType: "application/json",
	    dataType: "text",
	    data: JSON.stringify({
	    	id: getUrlParameter("id")
	    }),
	    success: function(r) {
			data = JSON.parse(r);
			console.log(data);
			for (i = 0; i < data.length; i++) {
				let info = data[i].trim().split('###');
				console.log(info);
				
				if (!info[0].trim()) {
					continue;
				}

				users_added[info[0].trim()] = info[1].trim();
			}
			update();
	    },
	    error: function(xhr, status, error) {
	    	console.log(this.data);
			alert(error);
		}
	});

	$(document).on('click', '#nowe-zadanie-btn', function() {
		let post_data = {
			"task_update": $('#task_update_input').val(),
			"task_name": $('#task_name_input').val(),
			"task_type": $('#task_type_input').val(),
			"task_importance": $('#task_importance_input').val(),
			"client_name": $('#find_name').val(),
			"client_last_name": $('#find_last_name').val(),
			"task_description": $('#task_description_input').val(),
			"users_added": users_added,
			"task_completion_date": $('#task_completion_date_input').val(),
			"task_completion_time": $('#task_completion_time_input').val()
		};

		if (!post_data['task_name']) {
			post_data['task_name'] = "nazwa zadania";
		}


		if (!post_data['task_description']) {
			post_data['task_description'] = "opis...";
		}

		console.log("PRZESYŁAM: ");
		console.log(users_added);

		$.redirect('save_in_database/task_update.php', post_data);
	});

	$('#find_name,#find_last_name').on("change paste keyup", function() {
		clearTimeout(wto);
		wto = setTimeout(function() {
			let find_data = {
			    "first_name": $('#find_name').val(),
			    "last_name": $('#find_last_name').val()
			};

			if (!find_data["first_name"] && !find_data["last_name"]) {
				return;
			}

			let data = JSON.stringify(find_data);

			$.ajax({
			    url: '/save_in_database/search_users_clients.php',
			    type: 'POST',
			    contentType: "application/json",
			    dataType: "text",
			    data: data,
			    success: function(r) {
					response = JSON.parse(r);
			        
			        $('#client_list').children().remove();
					for (var i = 0; i < response.length; i++) {
						if (!response[i]) {
							return;
						}
 						$('#client_list').append(
 							'<a id="client-' + i + '" href="#" onclick="return false;">' + response[i] + '</a><br>');
 						$('#client_list').on('click', '#client-' + i, function() {
							var nazwisko_full = $(this).text().split(' ');

							$('#find_name').val(nazwisko_full[0]); 
							$('#find_last_name').val(nazwisko_full.slice(1).join(" "));
						});
					}
			    },
			    error: function(xhr, status, error) {
			    	console.log(this.data);
					alert(error);
				}
			});
			
		}, 200);
	});

	function update() {
		$("#added_users_list").children().off().remove();
		Object.keys(users_added).forEach(function(key,index) {
		    $("#added_users_list").append('<span id="added-user-' + index + '"><a class="delete-btn" id="added-user-' + index + '-link" href="#" onclick="return false;">USUŃ</a> ' + key.trim() + '<br></span>');
			$('#added_users_list').off('click', '#added-user-' + index+ '-link');
			$('#added_users_list').on('click', '#added-user-' + index+ '-link', function(e) {
				delete users_added[key.trim()];
				update();
			});
		});
		console.log(users_added);
	}


	$('#users_hooked').on("change paste keyup", function() {
		clearTimeout(wto);
		wto = setTimeout(function() {
			let find_data = {
				"user": $('#users_hooked').val()
			}

			if (!find_data["user"]) {
				return;
			}

			let data = JSON.stringify(find_data);

			$.ajax({
				url: '/save_in_database/search_users_clients.php',
			    type: 'POST',
			    contentType: "application/json",
			    dataType: "text",
			    data: data,
			    success: function(r) {
					response = JSON.parse(r);
			        
			        $('#user_list').children().remove();
					for (var i = 0; i < response.length; i++) {
						if (!response[i]) {
							return;
						}
 						$('#user_list').append('<span id="user-' + i + '"><a id="user-' + i + '-link" href="#" onclick="return false;">DODAJ</a> ' + response[i] + '<br></span>');
 						$('#user_list').on('click', '#user-' + i + '-link', function() {
							let username = $(this).parent().clone().children().remove().end().text().trim();
							if (!username) {
								return;
							}

							users_added[username.split('#')[0]] = username.split('#')[1];
							update();
						});
					}
			    },
			    error: function(xhr, status, error) {
			    	console.log(this.data);
					alert(error);
				}
			});
		}, 200);
	});
</script>
</body>
</html>