<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true)
{
	header("Location: index.php");
	exit();
}
$server_error = $nazwa_albumu = $error = "";
$server_e_display = $e_display = "none";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$nazwa_albumu = trim(htmlspecialchars(filter_input(INPUT_POST, "nazwa_albumu"), ENT_QUOTES, 'UTF-8'));
	if ($nazwa_albumu == filter_input(INPUT_POST, "nazwa_albumu"))
	{
		if(strlen($nazwa_albumu) > 3 && strlen($nazwa_albumu) < 100)
		{
			require_once 'database.php';
			if ($addalbum = $mysqli->prepare("INSERT INTO albumy(id, tytul, data, id_uzytkownika) VALUES (NULL, ?, NOW(), ?)"))
			{
				$addalbum->bind_param("si", $nazwa_albumu, $_SESSION['user_id']);
				$addalbum->execute();
				$addalbum->close();
				$id_albumu = $mysqli->insert_id;
				if($albumQuery = $mysqli->prepare('SELECT id, tytul, data, id_uzytkownika FROM albumy WHERE id = ?'))
				{
					$albumQuery->bind_param("i", $id_albumu);
					$albumQuery->execute();
					
					$result = $albumQuery->get_result();
					$album = $result->fetch_assoc();
					$albumQuery->close();

					if($album)
					{
						if (!file_exists('img/'.$id_albumu)) 
						{
							mkdir('img/'.$id_albumu, 0777, true);
							$_SESSION['selected_album'] = $id_albumu;
							header("Location: dodaj-foto.php");
							$mysqli->close();
							exit;
						}
					}
				}

				//błąd dodawania po stronie serwera
				$server_error = "Wystąpił błąd, spróbuj ponownie później.";
				$server_e_display = "block";
				$mysqli->close();
			}
			else
			{
				//błąd dodawania po stronie serwera
				$server_error = "Wystąpił błąd, spróbuj ponownie później.";
				$server_e_display = "block";
			}
		}
	}
	$error = "Nazwa albumy musi zawierać od 3 do 100 znaków bez początkowych i końcowych spacji.";
	$e_display = "block";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Dodaj album | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona dodawania albumu">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="css/main.css" type="text/css">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <div id="header_text_wrapper">
    		<h1>Dodaj album</h1>
        </div>
		<nav>
			<ul>
				<li><a href="galeria.php">Galeria</a></li>
				<li><a href="dodaj-album.php">Załóź album</a></li>
				<li><a href="dodaj-foto.php">Dodaj zdjęcie</a></li>
				<li><a href="top-foto.php">Najlepiej oceniane</a></li>
				<li><a href="nowe-foto.php">Najnowsze</a></li>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="konto.php">Moje konto</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="wyloguj.php">Wyloguj się</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['uprawnienia']) && ($_SESSION['uprawnienia'] == 'moderator' || $_SESSION['uprawnienia'] == 'administrator')) echo '<li><a href="admin/index.php">Panel administracyjny</a></li>'?>
			</ul>
		</nav>
    </header>
    <main>
		<h3 style="display: <?=$server_e_display?>" class='error'><?=$server_error?></h3>
		<div class="form-wrapper">
			<form id="album_form" action="dodaj-album.php" method="post">
				<input class="nazwa_albumu" name="nazwa_albumu" type="text" placeholder="Wpisz nazwę albumu" value="<?=$nazwa_albumu?>" required>
				<span style="display: <?=$e_display?>" class='error'><?=$error?></span>

				<input class="dodaj-album" name="dodaj-album" type="submit" value="Dodaj album">
			</form>
		</div>
    </main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>