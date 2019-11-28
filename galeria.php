<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Galeria | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona główna projektu aplikacji webowej">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="css/main.css" type="text/css">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <div id="header_text_wrapper">
            <h1>Galeria</h1>
        </div>
        <nav>
            <ul>
                <li><a href="galeria.php">Galeria</a></li>
                <li><a href="dodaj-album.php">Załóź album</a></li>
                <li><a href="dodaj-foto.php">Dodaj zdjęcie</a></li>
                <li><a href="top-foto.php">Najlepiej oceniane</a></li>
                <li><a href="nowe-foto.php">Najnowsze</a></li>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="konto.php">Moje konto</a></li>'?>
                <?php if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) echo '<li><a href="index.php">Zaloguj się</a></li>'?>
                <?php if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) echo '<li><a href="index.php">Rejestracja</a></li>'?>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="wyloguj.php">Wyloguj się</a></li>'?>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['uprawnienia']) && ($_SESSION['uprawnienia'] == 'moderator' || $_SESSION['uprawnienia'] == 'administrator')) echo '<li><a href="admin/index.php">Panel administracyjny</a></li>'?>
            </ul>
        </nav>
    </header>
    <main>
    <?php
        function filter($get_name) {return htmlspecialchars(filter_input(INPUT_GET, $get_name), ENT_QUOTES, 'UTF-8');}

        $strona = isset($_GET['strona']) ? filter("strona") : 1;
		$order_by = filter("sortuj_wg");
		$order = (filter("kolejnosc") == "rosnaco" ? "ASC" : "DESC");
        $ok = true;

        $order == "rosnaco" ? $order = "ASC" : $order = "DESC";

        if(ctype_digit($strona))
            $ok = false;

        if($ok)
        {
            $pomin = ($strona * 20) - 20;
            require_once 'database.php';

            switch($order_by)
            {
                case 'data': $order_by = "a.data"; break;
                case 'nick': $order_by = "u.login"; break;
                default: $order_by = "a.tytul";
            }
            $order_by_query = "ORDER BY $order_by $order";

            if($albumQuery = $mysqli->prepare("SELECT 
                                                    a.id AS id,
                                                    a.tytul,
                                                    a.data,
                                                    a.id_uzytkownika,
                                                    z.id AS z_id,
                                                    z.id_albumu,
                                                    z.zaakceptowane,
                                                    u.id AS u_id,
                                                    u.login
                                                FROM 
                                                    albumy a
                                                    join zdjecia z
                                                        ON a.id = z.id_albumu
                                                    join uzytkownicy u
                                                        ON a.id_uzytkownika = u.id
                                                WHERE z.zaakceptowane = 1 
                                                GROUP BY a.id 
                                                $order_by_query
                                                LIMIT 20 OFFSET ?"))
            {
                $albumQuery->bind_param("i", $pomin);
                $albumQuery->execute();
                
                $result = $albumQuery->get_result();
                $albumQuery->close();
    
                while($album = $result->fetch_assoc())
                {
                    /*var_dump($album);
                    echo "<br>";*/
                    echo "<div class='album' album_id='".$album['id']."'>".$album['tytul']."</div>";
                }
            }
        }
    ?>
    </main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>