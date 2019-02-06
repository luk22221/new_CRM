


<nav class="navbar navbar-dark bg-dark" style="background-color: #babcbc;padding-top:5px;padding-right:5px;">
<div id="" style="color: black; width: 100%;">
	<ul class="nav navbar-nav" style="color: black; font-size: 18px; list-style-type: none;">
		<!-- <li><a class="float menuli nav navbar-nav" style="color: black;" font-size: 19px;" href="/"><img src="", alt="HOME"/></a></li> -->
		<li class="float navbar-brand" ><a style="color: black;" href="../index.php">Strona główna</a></li>
		<li class="float navbar-brand"><a style="color: black;" href="../clients.php">Klienci</a></li >
		<li class="float navbar-brand"><a style="color: black;" href="../new_task.php">Nowe zadanie</a></li>
		<?php if (($_SESSION['privilege'])== 1) {?>
		<li class="float navbar-brand"><a style="color: black;" href="../admin/management.php">Zarządzaj użytkownikami</a></li>
			<?php } ?>
	</ul>
 	</div>
 	<?php
 	echo '<div class="floatr" style="float: right;color: black; font-size: 14px;">' . $_SESSION['user'] . '<br /><a style="color: black; font-size: 14px; list-style-type: none;"  href="../config/logout.php">Wyloguj się</a><br />'; ?>
 	<a style="color: black; font-size: 14px; list-style-type: none;" href="../account_settings.php">ustawienia konta</a></div>

 	
 	<div class="clear")></div>
</div>
</nav>