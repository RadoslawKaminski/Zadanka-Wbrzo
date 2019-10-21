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
    <style>
		main
			{position:relative;}
		main a
		{
			display:inline-block;
			border-radius: 30px;
			border: 4px solid #636363;
			color: #525252;
			font-weight: bold;
			padding: 20px;
			font-size:40px;
			position:absolute;
			top:50%;
			left:50%;
			transform:translate(-50%, -50%);
			background-color: #f2bc8e;
		}
    </style>
</head>
<body>
    <header>
        <div id="header_text_wrapper">
			<h1>Poprawna rejestracja!</h1>
		</div>
    </header>
    <main>
    	<a href="galeria.php">Przejdź do galerii</a>
    </main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>