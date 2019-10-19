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
    <h1>Galeria</h1>
    <a href="wyloguj.php">Wyloguj</a>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>