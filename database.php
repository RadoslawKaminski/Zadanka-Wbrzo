<?php
$mysqli = new mysqli("localhost", "root", "", "kaminski_4Tb");

if ($mysqli->connect_errno) {
    echo "Błąd połączenia nr: " . $mysqli->connect_errno;
    echo "Opis błędu: " . $mysqli->connect_error;
    exit();
} 
$mysqli->query('SET NAMES utf8');
$mysqli->query('SET CHARACTER SET utf8');
$mysqli->query("SET collation_connection = utf8_polish_ci");