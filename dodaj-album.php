<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true)
{
	header("Location: index.php");
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
    <h1>Dodaj album</h1>
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
        <ul>
    </nav>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>