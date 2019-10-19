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
			<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zar_login']) ? $_SESSION['zar_login'] : "")?>" required>
			<?php if(isset($_SESSION['error']['zar_login'])) echo "<span class='error'>".$_SESSION['error']['zar_login']."</span>";?><br>
			
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zar_haslo']) ? $_SESSION['zar_haslo'] : "")?>" required>
			<?php if(isset($_SESSION['error']['zar_haslo'])) echo "<span class='error'>".$_SESSION['error']['zar_haslo']."</span>";?><br>

			<input class="p_haslo" name="p_haslo" type="password" placeholder="Powtórz hasło" value="<?=(isset($_SESSION['p_haslo']) ? $_SESSION['p_haslo'] : "")?>" required>
			<?php if(isset($_SESSION['error']['p_haslo'])) echo "<span class='error'>".$_SESSION['error']['p_haslo']."</span>";?><br>

			<input class="email" name="email" type="email" placeholder="Wpisz email" value="<?=(isset($_SESSION['email']) ? $_SESSION['email'] : "")?>" required>
			<?php if(isset($_SESSION['error']['email'])) echo "<span class='error'>".$_SESSION['error']['email']."</span>";?><br>

			<input class="zarejestruj" name="zarejestruj" type="submit" value="Zarejestruj">
		</form>
		<form id="zal_form" action="logowanie.php" method="post" enctype="multipart/form-data">
			<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zal_login']) ? $_SESSION['zal_login'] : "")?>" required><br>
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zal_haslo']) ? $_SESSION['zal_haslo'] : "")?>" required><br>
			<input class="zaloguj" name="zaloguj" type="submit" value="Zaloguj">
			<?php if(isset($_SESSION['error']['login_pass'])) echo "<br><span class='error'>".$_SESSION['error']['login_pass']."</span>";?>
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