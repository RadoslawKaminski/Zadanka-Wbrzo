<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
	$login=filter('login');
	$haslo=filter('haslo');
	
	$_SESSION['error'] = Array();
	
	if($login != $_POST['login'] || !ctype_alnum($login) || strlen($login) < 6 || strlen($login) > 20)
	{
		$_SESSION['error']['zal_login'] = "Login musi zawierać od 6 do 20 znaków, tylko litery i cyfry.";
	}
	if($haslo != $_POST['haslo'] || strlen($haslo) < 6 || strlen($haslo) > 20 || !preg_match('/[a-z]/', $haslo) || !preg_match('/[A-Z]/', $haslo) || !preg_match('/[0-9]/', $haslo))
	{
		$_SESSION['error']['zal_haslo'] = "Hasło musi zawierać od 6 do 20 znaków, minimum 1 duża litera, 1 mała litera i 1 cyfra.";
    }
    
	if(empty($_SESSION['error']))
	{
        require_once 'database.php';
        
        if($userQuery = $mysqli->prepare('SELECT id, login, haslo, email, aktywny FROM uzytkownicy WHERE login = ?'))
        {
            $userQuery->bind_param("s", $login);
            $userQuery->execute();
            
            $result = $userQuery->get_result();
            $user = $result->fetch_assoc();
            $userQuery->close();
            if($user && $user['haslo'] === md5($haslo))
            {
                if($user['aktywny'] === 1)
                {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_login'] = $user['login'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    header('Location: galeria.php');
                }
                else
                {
                    //użytkownik zbanowany
                    $_SESSION['error']['ban'] = true;
                    header('Location: index.php');
                }
            }
            else
            {
                //niepoprawny login lub hasło
                $_SESSION['error']['login_pass'] = "Niepoprawny login lub hasło";
                header('Location: index.php');
            }
        }
        else
        {
            //błąd logowania
			$_SESSION['error']['server'] = "Wystąpił błąd, spróbuj ponownie później.";
            header('Location: index.php');
        }

		$mysqli->close();
	}
	else
	{
		//wystąpiły błędy spowodowane podaniem złych danych
        header('Location: index.php');
	}
}
else
{
	//to nie prośba o zalogowanie
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
	{
		header("Location: galeria.php");
	}
	else
		header("Location: index.php");
}