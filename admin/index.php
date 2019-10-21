<?php
session_start();
if(!isset($_SESSION['logged_in']) || 
        $_SESSION['logged_in'] != true || 
    !isset($_SESSION['uprawnienia']) ||
        ($_SESSION['uprawnienia'] != 'moderator' && $_SESSION['uprawnienia'] != 'administrator'))
{
	header("Location: index.php");
	exit();
}
?>
<html>
<head>
    <title>Panel administracyjny | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona panelu administracyjnego projektu aplikacji webowej">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="../css/main.css" type="text/css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <div id="header_text_wrapper">
			<h1>Panel administracyjny</h1>
		</div>
		<nav>
			<ul>
				<li><a href="../galeria.php">Galeria</a></li>
				<li><a href="../dodaj-album.php">Załóź album</a></li>
				<li><a href="../dodaj-foto.php">Dodaj zdjęcie</a></li>
				<li><a href="../top-foto.php">Najlepiej oceniane</a></li>
				<li><a href="../nowe-foto.php">Najnowsze</a></li>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="../konto.php">Moje konto</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="../wyloguj.php">Wyloguj się</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['uprawnienia']) && ($_SESSION['uprawnienia'] == 'moderator' || $_SESSION['uprawnienia'] == 'administrator')) echo '<li><a href="index.php">Panel administracyjny</a></li>'?>
			<ul>
		</nav>
	</header>
	<main>
	</main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>