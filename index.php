<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
{
	header("Location: galeria.php");
	exit();
}
$zar_login_e_display = $zar_haslo_e_display = $p_haslo_e_display = $email_e_display = $login_pass_e_display = $ban_e_display = $server_e_display = 'none';
$zar_login_error = $zar_haslo_error = $p_haslo_error = $email_error = $login_pass_error = $ban_error = $server_error = '';
if(isset($_SESSION['error']['zar_login']))
	{$zar_login_e_display = 'inline-block'; $zar_login_error = $_SESSION['error']['zar_login'];}
if(isset($_SESSION['error']['zar_haslo']))
	{$zar_haslo_e_display = 'inline-block'; $zar_haslo_error = $_SESSION['error']['zar_haslo'];}
if(isset($_SESSION['error']['p_haslo']))
	{$p_haslo_e_display = 'inline-block'; $p_haslo_error = $_SESSION['error']['p_haslo'];}
if(isset($_SESSION['error']['email']))
	{$email_e_display = 'inline-block'; $email_error = $_SESSION['error']['email'];}
if(isset($_SESSION['error']['login_pass']))
	{$login_pass_e_display = 'block'; $login_pass_error = $_SESSION['error']['login_pass'];}
if(isset($_SESSION['error']['ban']))
	{$ban_e_display = 'block'; $ban_error = $_SESSION['error']['ban'];}
if(isset($_SESSION['error']['server']))
	{$server_e_display = 'block'; $server_error = $_SESSION['error']['server'];}
?>
<html>
	<head>
		<title>Index | Projekt aplikacji webowej</title>
		<meta name="description" content="Strona logowania i rejestracji projektu aplikacji webowej">
		<meta name="keywords" content="">

		<link rel="stylesheet" href="css/main.css" type="text/css">
		<link rel="stylesheet" href="css/header.css" type="text/css">
		<link rel="stylesheet" href="css/index.css" type="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
    	<header>
    		<div id="header_text_wrapper">
    			<h1>Zaloguj się lub zarejestruj</h1>
    		</div>
			<nav>
				<ul>
					<li><a href="galeria.php">Galeria</a></li>
					<li><a href="dodaj-album.php">Załóź album</a></li>
					<li><a href="dodaj-foto.php">Dodaj zdjęcie</a></li>
					<li><a href="top-foto.php">Najlepiej oceniane</a></li>
					<li><a href="nowe-foto.php">Najnowsze</a></li>
					<li><a href="index.php">Zaloguj się</a></li>
					<li><a href="index.php">Rejestracja</a></li>
				<ul>
			</nav>
		</header>
		<h3 style="display: <?=$server_e_display?>" class='error'><?=$server_error?></h3>
		<form id="zar_form" action="rejestracja.php" method="post">
			<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zar_login']) ? $_SESSION['zar_login'] : "")?>" required>
			<span style="display: <?=$zar_login_e_display?>" class='error'><?=$zar_login_error?></span><br>
			
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zar_haslo']) ? $_SESSION['zar_haslo'] : "")?>" required>
			<span style="display: <?=$zar_haslo_e_display?>" class='error'><?=$zar_haslo_error?></span><br>

			<input class="p_haslo" name="p_haslo" type="password" placeholder="Powtórz hasło" value="<?=(isset($_SESSION['p_haslo']) ? $_SESSION['p_haslo'] : "")?>" required>
			<span style="display: <?=$p_haslo_e_display?>" class='error'><?=$p_haslo_error?></span><br>

			<input class="email" name="email" type="email" placeholder="Wpisz email" value="<?=(isset($_SESSION['email']) ? $_SESSION['email'] : "")?>" required>
			<span style="display: <?=$email_e_display?>" class='error'><?=$email_error?></span><br>

			<input class="zarejestruj" name="zarejestruj" type="submit" value="Zarejestruj">
		</form>
		<form id="zal_form" action="logowanie.php" method="post">
			<div>
				<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zal_login']) ? $_SESSION['zal_login'] : "")?>" required>
				<span style="display: none" class='error'></span><br>
				<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zal_haslo']) ? $_SESSION['zal_haslo'] : "")?>" required>
				<span style="display: none" class='error'></span>
			</div>
			<span style="display: <?=$login_pass_e_display?>" class='error'><?=$login_pass_error?></span>
			<span style="display: <?=$ban_e_display?>" class='error'><?=$ban_error?></span>
			<input class="zaloguj" name="zaloguj" type="submit" value="Zaloguj">
		</form>
		<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
		<script type="text/javascript">
		
			$('#zar_form').on('submit', function(event){
				var login = $('#zar_form .login').val(),
					haslo = $('#zar_form .haslo').val(),
					p_haslo = $('#zar_form .p_haslo').val(),
					email = $('#zar_form .email').val();

				$('.error').css('display', 'none');

				if(!(/^[A-Za-z0-9]*$/.test(login)) || login.length < 6 || login.length > 20)
					$('#zar_form .login').next().css('display', 'inline-block').text("Login musi zawierać od 6 do 20 znaków, tylko litery i cyfry.");
				if(!login)
					$('#zar_form .login').next().css('display', 'inline-block').text("Login jest wymagany.");
				if(haslo.length < 6 || haslo.length > 20 || !(/[a-z]/.test(haslo)) || !(/[A-Z]/.test(haslo)) || !(/\d/.test(haslo)))
					$('#zar_form .haslo').next().css('display', 'inline-block').text("Hasło musi zawierać od 6 do 20 znaków, minimum 1 duża litera, 1 mała litera i 1 cyfra.");
				if(!haslo)
					$('#zar_form .haslo').next().css('display', 'inline-block').text("Hasło jest wymagane.");
				if(p_haslo != haslo)
					$('#zar_form .p_haslo').next().css('display', 'inline-block').text("Hasła nie są takie same.");
				if(!p_haslo)
					$('#zar_form .p_haslo').next().css('display', 'inline-block').text("Powtórzenie hasła jest wymagane.");
				if(!(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email)))
					$('#zar_form .email').next().css('display', 'inline-block').text("Nieprawidłowy email.");
				if(!email)
					$('#zar_form .email').next().css('display', 'inline-block').text("Email jest wymagany.");
				
				if($('.error[style*="display: inline-block"]').length == 0)
					return
				else
					event.preventDefault();
			});
			
			$('#zal_form').on('submit', function(event){
				var login = $('#zal_form .login').val(),
					haslo = $('#zal_form .haslo').val();

				$('.error').css('display', 'none');

				if(!(/^[A-Za-z0-9]*$/.test(login)) || login.length < 6 || login.length > 20)
					$('#zal_form .login').next().css('display', 'inline-block').text("Login musi zawierać od 6 do 20 znaków, tylko litery i cyfry.");
				if(!login)
					$('#zal_form .login').next().css('display', 'inline-block').text("Login jest wymagany.");
				if(haslo.length < 6 || haslo.length > 20 || !(/[a-z]/.test(haslo)) || !(/[A-Z]/.test(haslo)) || !(/\d/.test(haslo)))
					$('#zal_form .haslo').next().css('display', 'inline-block').text("Hasło musi zawierać od 6 do 20 znaków, minimum 1 duża litera, 1 mała litera i 1 cyfra.");
				if(!haslo)
					$('#zal_form .haslo').next().css('display', 'inline-block').text("Hasło jest wymagane.");
				
				if($('.error[style*="display: inline-block"]').length == 0)
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