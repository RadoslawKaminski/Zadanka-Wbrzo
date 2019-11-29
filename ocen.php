<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true)
{
	header("Location: index.php");
	exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}

    $ocena = filter("ocena");
    $zdjecie_id = filter("zdjecie_id");
    $user_id = $_SESSION['user_id'];

	if(ctype_digit($ocena) && ctype_digit($zdjecie_id))
	{
		if($ocena > 0 && $ocena <= 10)
		{
            require_once 'database.php';
			if ($mysqli->query("INSERT INTO zdjecia_oceny(id_zdjecia, id_uzytkownika, ocena) VALUES ($zdjecie_id, $user_id, $ocena)"))
			{
                echo 'no_errors';
			}
		}
	}
}