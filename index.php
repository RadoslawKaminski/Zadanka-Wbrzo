<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
{
	header("Location: galeria.php");
	exit();
}
$zar_login_error = $zar_haslo_error = $p_haslo_error = $email_error = $login_pass_error = $ban_error = $server_error = 'none';
if(!empty($_SESSION['error']['zar_login']))
	$zar_login_error = 'inline-block';
if(!empty($_SESSION['error']['zar_haslo']))
	$zar_haslo_error = 'inline-block';
if(!empty($_SESSION['error']['p_haslo']))
	$p_haslo_error = 'inline-block';
if(!empty($_SESSION['error']['email']))
	$email_error = 'inline-block';
if(!empty($_SESSION['error']['login_pass']))
	$login_pass_error = 'inline-block';
if(!empty($_SESSION['error']['ban']))
	$ban_error = 'inline-block';
if(!empty($_SESSION['error']['server']))
	$server_error = 'inline-block';
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
		<h3 style="display: <?=$server_error?>" class='error'><?=$_SESSION['error']['server']?></h3>
		<form id="zar_form" action="rejestracja.php" method="post">
			<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zar_login']) ? $_SESSION['zar_login'] : "")?>" required>
			<span style="display: <?=$zar_login_error?>" class='error'><?=$_SESSION['error']['zar_login']?></span><br>
			
			<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zar_haslo']) ? $_SESSION['zar_haslo'] : "")?>" required>
			<span style="display: <?=$zar_haslo_error?>" class='error'><?=$_SESSION['error']['zar_haslo']?></span><br>

			<input class="p_haslo" name="p_haslo" type="password" placeholder="Powtórz hasło" value="<?=(isset($_SESSION['p_haslo']) ? $_SESSION['p_haslo'] : "")?>" required>
			<span style="display: <?=$p_haslo_error?>" class='error'><?=$_SESSION['error']['p_haslo']?></span><br>

			<input class="email" name="email" type="email" placeholder="Wpisz email" value="<?=(isset($_SESSION['email']) ? $_SESSION['email'] : "")?>" required>
			<span style="display: <?=$email_error?>" class='error'><?=$_SESSION['error']['email']?></span><br>

			<input class="zarejestruj" name="zarejestruj" type="submit" value="Zarejestruj">
		</form>
		<form id="zal_form" action="logowanie.php" method="post">
			<div>
				<input class="login" name="login" type="text" placeholder="Wpisz login" value="<?=(isset($_SESSION['zal_login']) ? $_SESSION['zal_login'] : "")?>" required><br>
				<input class="haslo" name="haslo" type="password" placeholder="Wpisz hasło" value="<?=(isset($_SESSION['zal_haslo']) ? $_SESSION['zal_haslo'] : "")?>" required>
			</div>
			<span style="display: <?=$login_pass_error?>" class='error'><?=$_SESSION['error']['login_pass']?></span>
			<span style="display: <?=$ban_error?>" class='error'><?=$_SESSION['error']['ban']?></span>
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