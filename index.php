<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
{
	header("Location: galeria.php");
	exit();
}
?>
<html>
	<head>
			<title>AAaaa</title>
			<meta charset="utf-8">
			<style>
			</style>
	</head>
	<body>
    	<h1>Zaloguj się lub zarejestruj</h1>
		<form id="zar_form" action="rejestracja.php" method="post" enctype="multipart/form-data">
			<input class="login" name="login" type="text" placeholder="Wpisz login" value=""><br>
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value=""><br>
			<input class="p_haslo" name="p_haslo" type="password" placeholder="Powtórz hasło" value=""><br>
			<input class="email" name="email" type="email" placeholder="Wpisz email" value=""><br>
			<input class="zarejestruj" name="zarejestruj" type="submit" value="Zarejestruj">
		</form>
		<br>
		<form id="zal_form" action="logowanie.php" method="post" enctype="multipart/form-data">
			<input class="login" name="login" type="text" placeholder="Wpisz login" value=""><br>
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value=""><br>
			<input class="zaloguj" name="zaloguj" type="submit" value="Zaloguj">
		</form>
		<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
		<script type="text/javascript">
			$('#zar_form').on('submit', function(event){
				var login = $('#zar_form #login').val(),
					haslo = $('#zar_form #haslo').val(),
					p_haslo = $('#zar_form #p_haslo').val(),
					email = $('#zar_form #email').val();
				var ok = true;
				
				//walidacja rejestracji tutaj
				
				if(ok)
					return
				else
					event.preventDefault();
			});
			
			$('#zal_form').on('submit', function(event){
				var login = $('#zal_form #login').val(),
					haslo = $('#zal_form #haslo').val();
				var ok = true;
				
				//walidacja logowania tutaj
				
				if(ok)
					return
				else
					event.preventDefault();
			});
		</script>
		<footer>
			Radosław Kamiński 4Tb
		</footer>
	</body>
</html>