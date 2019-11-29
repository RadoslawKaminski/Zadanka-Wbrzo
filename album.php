<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Album | Projekt aplikacji webowej</title>
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
            <h1>Album</h1>
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
        <a class='go-galery' href="galeria.php">Powrót do galerii</a><br>
        <?php
            function filter($get_name) {return htmlspecialchars(filter_input(INPUT_GET, $get_name), ENT_QUOTES, 'UTF-8');}

            $strona = isset($_GET['strona']) ? filter("strona") : 1;
            $album_id = filter("album_id");
            
            $ok = true;

            if(!(ctype_digit($strona) || is_int($strona)))
                $ok = false;
            if(!(ctype_digit($album_id) || is_int($album_id)))
                $ok = false;

            if($ok)
            {
                require_once 'database.php';

                $pomin = ($strona * 20) - 20;
                
                if($zdjecieQuery = $mysqli->prepare("SELECT 
                                                            z.id,
                                                            z.id_albumu,
                                                            count(z.id) AS countId
                                                        FROM zdjecia z
                                                        WHERE z.id_albumu = ?
                                                        GROUP BY z.id
                                                        ORDER BY z.id DESC
                                                        LIMIT $pomin, 18446744073709551615"))
                {
                    require_once 'class.img.php';

                    $zdjecieQuery->bind_param("i", $album_id);
                    $zdjecieQuery->execute();
                    
                    $result = $zdjecieQuery->get_result();

                    $ile_stron = (int)floor((($result->num_rows)+$pomin)/20)+1;

                    $wyswietlono_ilosc = 0;
                    while(($zdjecie = $result->fetch_assoc()))
                    {
                        if($wyswietlono_ilosc >= 20)
                            break;
                        $img = new Image("img/".$zdjecie['id_albumu']."/".$zdjecie['id']);
                        $img->SetMaxSize(180);

                        ob_start();
                        $img->Send();
                        $output = base64_encode(ob_get_contents());
                        ob_end_clean();

                        echo    "<section class='zdjecie' zdjecie_id='".$zdjecie['id']."'>
                                    <a href='foto.php?zdjecie_id=".$zdjecie['id']."'>
                                        <img 
                                            src='data:image/jpeg;base64, $output' 
                                            alt='Miniatura zdjęcia w albumie'
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
                            echo "<li><a href='?album_id=$album_id&strona=$i' ".($i == $strona ? "class='current-page'" : "").">$i</a></li>";
                        }
                    ?>                
                                    </ul>
                                </nav>
                    <?php
                    }
                }
            }
        ?>
        <br><a class='go-galery' href="galeria.php">Powrót do galerii</a>
    </main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
</body>
</html>