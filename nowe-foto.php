<html>
<head>
        <title>AAaaa</title>
        <meta charset="utf-8">
        <style>
        </style>
</head>
<body>
    <h1>Najnowsze zdjęcia</h1>
    <nav>
        <ul>
            <li><a href="galeria.php">Galeria</a></li>
            <li><a href="dodaj-album.php">Załóź album</a></li>
            <li><a href="dodaj-foto.php">Dodaj zdjęcie</a></li>
            <li><a href="top-foto.php">Najlepiej oceniane</a></li>
            <li><a href="nowe-foto.php">Najnowsze</a></li>
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="konto.php">Moje konto</a></li>'?>
            <?php if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) echo '<li><a href="index.php">Zaloguj się</a></li>'?>
            <?php if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != true) echo '<li><a href="index.php">Rejestracja</a></li>'?>
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="wyloguj.php">Wyloguj się</a></li>'?>
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['uprawnienia']) && ($_SESSION['uprawnienia'] == 'moderator' || $_SESSION['uprawnienia'] == 'administrator')) echo '<li><a href="admin/index.php">Panel administracyjny</a></li>'?>
        <ul>
    </nav>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>