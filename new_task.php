<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header("location: index.php");
include "config/connect.php";
	?>
<html lang="pl">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>CRM - nowe zadanie</title>
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
?>
<?php
if (isset($_SESSION['info_task']))
{
	echo '<div class="error">' . $_SESSION['info_task'] . '</div>';
	unset($_SESSION['info_task']);
}
?>
<div style="margin-right: 20%; margin-left: 20%;">
<form class="form-group" action="save_in_database/task_save.php" method="post">
<h3 style="text-align: center;">Dodaj nowe zadanie do bazy danych</h3>
<div class="form-group">
	<label for="">Nazwa zadania: </label> 
	 <input class="form-control" id="task_name_input" type="text" value="" name="name" />
</div>
<div class="form-group">	
	<label for="">Rodzaj zadania</label>
	<select class="form-control" id="task_type_input" name="type_task">
		<option>inny</option>
		<option>Standardowy projekt strony internetowej</option>
		<option>Telefon</option>
		<option>Spotkanie</option>
	</select>
</div>
<div class="form-group">
	<label for="">Stopień ważności zadania</label>
	<select class="form-control" id="task_importance_input" name="importance">
		<option>inny</option>
		<option>ważne</option>
		<option>średnio ważne</option>
		<option>mało ważne</option>
	</select>
</div>
<div class="form-group"> 
	<label for="">klient</label>
	<input class="form-control" type="text" id="find_name" value="" name="first_name" />
	<input class="form-control" type="text" id="find_last_name" value="" name="last_name" />
	<div id="client_list">
	</div>
</div>
<div class="form-group">	
	<label for="">Uzytkownicy podpięci do zadania</label>
	<input class="form-control" type="text" id="users_hooked" value="" name="" />
	<div id="user_list"></div>
	<div id="added_users_list" name="added_users_list"></div>
	
</div>
<div class="form-group"> 
	<label for="">Opis zadania</label>
	<input class="form-control" id="task_description_input" type="text" value="" name="description_task" />
</div>
<div class="form-group">	
	<label for="">Planowana data i czas wykonania zadania</label>
	<input class="form-control" id="task_completion_date_input" type="date" name="date"  />
	<input class="form-control" id="task_completion_time_input" type="time" name="time" value="12:00" />
</div>
	<input type="hidden" name="flag_done" value="done">


	
	<button class="btn btn-primary" type="submit" id="nowe-zadanie-btn" onclick="return false;">dodaj nowe zadanie</button>
	
</form>

<?php
include "include/footer.php";
?>
 
<script>
	var wto;
	var response = [];
	var users_added = {};

	$('#nowe-zadanie-btn').click(function() {
		let post_data = {
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

		
		$.redirect('save_in_database/task_save.php', post_data);
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
		    $("#added_users_list").append('<span id="added-user-' + index + '"><a class="delete-btn" id="added-user-' + index + '-link" href="#" onclick="return false;">USUŃ</a> ' + key + '<br></span>');
			$('#added_users_list').off('click', '#added-user-' + index+ '-link');
			$('#added_users_list').on('click', '#added-user-' + index+ '-link', function(e) {
				delete users_added[key];
				update();
			});
		});
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
			    	console.log(r);
					response = JSON.parse(r);
			        
			        $('#user_list').children().remove();
					for (var i = 0; i < response.length; i++) {
						if (!response[i]) {
							return;
						}
 						$('#user_list').append('<span id="user-' + i + '"><a id="user-' + i + '-link" href="#" onclick="return false;">DODAJ</a> ' + response[i] + '<br></span>');
 						$('#user_list').on('click', '#user-' + i + '-link', function() {
							let username = $(this).parent().clone().children().remove().end().text();
							if (!username) {
								return;
							}
							console.log("USERNAME: " + username);

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