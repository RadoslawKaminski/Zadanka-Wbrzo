<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
	$login=filter('login');
	$haslo=filter('haslo');
	$p_haslo=filter('p_haslo');
	$email=filter('email');
	
	$_SESSION['error'] = Array();
	
	if($login != $_POST['login'] || !ctype_alnum($login) || strlen($login) < 6 || strlen($login) > 20)
	{
		$_SESSION['error']['zar_login'] = "Login musi zawierać od 6 do 20 znaków, tylko litery i cyfry.";
	}
	if($haslo != $_POST['haslo'] || strlen($haslo) < 6 || strlen($haslo) > 20 || !preg_match('/[a-z]/', $haslo) || !preg_match('/[A-Z]/', $haslo) || !preg_match('/[0-9]/', $haslo))
	{
		$_SESSION['error']['zar_haslo'] = "Hasło musi zawierać od 6 do 20 znaków, minimum 1 duża litera, 1 mała litera i 1 cyfra.";
	}
	if($p_haslo != $haslo)
	{
		$_SESSION['error']['p_haslo'] = "Hasła nie są takie same.";
	}
	if($email != $_POST['email'] || !filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$_SESSION['error']['email'] = "Nieprawidłowy email.";
	}
	
	require_once 'database.php';

	//sprawdzenie czy login nie jest już zajęty
		if($isLoginTakenQuerry = $mysqli->prepare('SELECT id, login, haslo, email FROM uzytkownicy WHERE login = ?'))
		{
			$isLoginTakenQuerry->bind_param("s", $login);
			$isLoginTakenQuerry->execute();
			
			$resultLogin = $isLoginTakenQuerry->get_result();
			$isLoginTaken = $resultLogin->fetch_assoc();
			$isLoginTakenQuerry->close();

			if($isLogin)
				$_SESSION['error']['login'] = "Ten login jest już zajęty.";
		}
		else
		{
			//błąd po stronie serwera
			$_SESSION['error']['server'] = "Wystąpił błąd, spróbuj ponownie później.";
		}
	////////////////////////////////////////////

	//sprawdzanie czy nie ma takiego emaila
		if($isEmailTakenQuery = $mysqli->prepare('SELECT id, login, haslo, email FROM uzytkownicy WHERE email = ?'))
		{
			$isEmailTakenQuery->bind_param("s", $email);
			$isEmailTakenQuery->execute();
			
			$resultEmail = $isEmailTakenQuery->get_result();
			$isEmailTaken = $resultEmail->fetch_assoc();
			$isEmailTakenQuery->close();

			if($isEmail)
				$_SESSION['error']['email'] = "Ten email jest już zajęty.";
		}
		else
		{
			//błąd po stronie serwera
			$_SESSION['error']['server'] = "Wystąpił błąd, spróbuj ponownie później.";
		}
	///////////////////////////////////////
	
	if(empty($_SESSION['error']))
	{
		if ($adduser = $mysqli->prepare("INSERT INTO uzytkownicy(id, login, haslo, email, zarejestrowany, uprawnienia, aktywny) VALUES (NULL, ?, ?, ?, NOW(), 'użytkownik', 1)"))
		{
			$enc_pass = md5($haslo);
			$adduser->bind_param("sss", $login, $enc_pass, $email);
			$adduser->execute();
			$adduser->close();
			if($userQuery = $mysqli->prepare('SELECT id, login, haslo, email FROM uzytkownicy WHERE login = ?'))
			{
				$userQuery->bind_param("s", $login);
				$userQuery->execute();
				
				$result = $userQuery->get_result();
				$user = $result->fetch_assoc();
				$userQuery->close();

				if($user)
				{
					$_SESSION['logged_in'] = true;
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['user_login'] = $user['login'];
					$_SESSION['user_email'] = $user['email'];
					
					header('Location: rejestracja-ok.php');
					$mysqli->close();
					exit;
				}
			}

			//błąd dodawania po stronie serwera
			$_SESSION['error']['server'] = "Wystąpił błąd, spróbuj ponownie później.";
			//usunięcie dodanego usera

			header('Location: index.php');
			$mysqli->close();
			exit;
		}
		else
		{
			//błąd dodawania po stronie serwera
			$_SESSION['error']['server'] = "Wystąpił błąd, spróbuj ponownie później.";
			header('Location: index.php');
		}
	}
	else
	{
		//wystąpiły błędy
        header('Location: index.php');
	}

	$mysqli->close();
}
else
{
	//to nie prośba o rejestrację
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
	{
		header("Location: galeria.php");
	}
	else
		header("Location: index.php");
}