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

    $komentarz = filter("komentarz");
    $zdjecie_id = filter("zdjecie_id");
    $user_id = $_SESSION['user_id'];
	if(ctype_digit($zdjecie_id))
	{
		require_once 'database.php';
		//w poleceniu nie było z jaką wartością początkową "zaakceptowany" dodawać komentarze, więc dodaję jako 1, dla większego komfortu użytkowania strony
		if ($addkom = $mysqli->prepare("INSERT INTO zdjecia_komentarze(id_zdjecia, id_uzytkownika, data, komentarz, zaakceptowany) VALUES ($zdjecie_id, $user_id, now(), ?, 1)"))
		{
			$addkom->bind_param("s", $komentarz);
			if($addkom->execute())
				echo 'no_errors';
			$addkom->close();
		}
	}
}