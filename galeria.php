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
    <link rel="stylesheet" href="css/galeria.css" type="text/css">
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
    <main id="container">
        <?php
            function filter($get_name) {return htmlspecialchars(filter_input(INPUT_GET, $get_name), ENT_QUOTES, 'UTF-8');}

            $strona = isset($_GET['strona']) ? filter("strona") : 1;
            $sortuj_wg = isset($_GET["sortuj_wg"]) ? filter("sortuj_wg") : "tytul";
            $kolejnosc = filter("kolejnosc");
            $order_by = "";
            $order = ($kolejnosc == "malejaco" ? "DESC" : "ASC");
            $ok = true;
            
            echo    "<section id='ustawienia'>Posortuj według: 
                        <a href='?sortuj_wg=tytul&kolejnosc=".($order == "DESC" ? "rosnaco" : "malejaco")."'>tytuł".($sortuj_wg == "tytul" ? ($order == "DESC" ? "▼" : "▲") : "")."</a> 
                        <a href='?sortuj_wg=data&kolejnosc=".($order == "DESC" ? "rosnaco" : "malejaco")."'>data".($sortuj_wg == "data" ? ($order == "DESC" ? "▼" : "▲") : "")."</a> 
                        <a href='?sortuj_wg=nick&kolejnosc=".($order == "DESC" ? "rosnaco" : "malejaco")."'>twórca".($sortuj_wg == "nick" ? ($order == "DESC" ? "▼" : "▲") : "")."</a>
                    </section>";

            if(!(ctype_digit($strona) || is_int($strona)))
                $ok = false;

            if($ok)
            {
                require_once 'database.php';

                switch($sortuj_wg)
                {
                    case 'data': $order_by = "a.data"; break;
                    case 'nick': $order_by = "u.login"; break;
                    default: $order_by = "a.tytul";
                }
                $order_by_query = "ORDER BY $order_by $order";
                $pomin = ($strona * 20) - 20;
                
                if($result = $mysqli->query("SELECT 
                                                    a.id AS id,
                                                    a.tytul,
                                                    a.data,
                                                    a.id_uzytkownika,
                                                    z.id AS z_id,
                                                    z.id_albumu,
                                                    z.zaakceptowane,
                                                    u.id AS u_id,
                                                    u.login,
                                                    count(a.id) AS countId
                                                FROM 
                                                    albumy a
                                                    join zdjecia z
                                                        ON a.id = z.id_albumu
                                                    join uzytkownicy u
                                                        ON a.id_uzytkownika = u.id
                                                WHERE z.zaakceptowane = 1 
                                                GROUP BY a.id 
                                                $order_by_query
                                                LIMIT $pomin, 18446744073709551615"))
                {
                    require_once 'class.img.php';

                    $ile_stron = (int)floor((($result->num_rows)+$pomin)/20)+1;

                    $wyswietlono_ilosc = 0;
                    while(($album = $result->fetch_assoc()))
                    {
                        if($wyswietlono_ilosc >= 20)
                            break;
                        $img = new Image("img/".$album['id']."/".$album['z_id']);
                        $img->SetMaxSize(180);

                        ob_start();
                        $img->Send();
                        $output = base64_encode(ob_get_contents());
                        ob_end_clean();

                        echo    "<section class='album' album_id='".$album['id']."'>
                                    <h3>".$album['tytul']."</h3>
                                    <a href='album.php?album_id=".$album['id']."'>
                                        <img 
                                            src='data:image/jpeg;base64, $output' 
                                            title='".$album['tytul'].", album użytkownika ".$album['login'].", utworzony ".$album['data']."' 
                                            alt='miniatura zdjęcia w albumie'
                                        >
                                    </a>
                                </section>";
                        $wyswietlono_ilosc++;
                    }
                    if($ile_stron > 1 || $strona > 1)
                    {?>
                                <nav id='paginacja'>
                                    <ul>
                    <?php
                        for($i=1; $i<=($ile_stron); $i++)
                        {
                            echo "<li><a href='?strona=$i&sortuj_wg=$sortuj_wg&kolejnosc=$kolejnosc' ".($i == $strona ? "class='current-page'" : "").">$i</a></li>";
                        }
                    ?>                
                                    </ul>
                                </nav>
                    <?php
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