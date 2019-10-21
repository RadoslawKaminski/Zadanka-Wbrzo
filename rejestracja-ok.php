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
    <title>Poprawna rejestracja! | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona informująca o poprawnym przebiegu rejestracji">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="css/main.css" type="text/css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Poprawna rejestracja!</h1>
    <a href="galeria.php">Galeria</a>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>