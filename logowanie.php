<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$_SESSION['zal_login'] = $_POST['login'];
    $_SESSION['zal_haslo'] = $_POST['haslo'];
    
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
	$login=filter('login');
	$haslo=filter('haslo');
	
	$_SESSION['error'] = Array();
    
    require_once 'database.php';
    
    if($userQuery = $mysqli->prepare('SELECT id, login, haslo, email, aktywny, uprawnienia FROM uzytkownicy WHERE login = ?'))
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
                $_SESSION['uprawnienia'] = $user['uprawnienia'];
                
                header('Location: galeria.php');
            }
            else
            {
                //użytkownik zbanowany
                $_SESSION['error']['ban'] = "Użytkownik o podanym loginie został zablokowany przez administratora.";
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
	//to nie prośba o zalogowanie
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
	{
		header("Location: galeria.php");
	}
	else
		header("Location: index.php");
}