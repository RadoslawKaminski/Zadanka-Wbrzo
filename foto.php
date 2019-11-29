<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Zdjęcie | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona główna projektu aplikacji webowej">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/foto.css" type="text/css">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <div id="header_text_wrapper">
            <h1>Zdjęcie</h1>
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

            $album_id = filter("album_id");
            $zdjecie_id = filter("zdjecie_id");
            
            echo "<a class='go-album' href='album.php?album_id=$album_id'>Powrót do listy zdjęć</a><br>";
            
            $ok = true;

            if(!(ctype_digit($album_id) || is_int($album_id)))
                $ok = false;
            if(!(ctype_digit($zdjecie_id) || is_int($zdjecie_id)))
                $ok = false;

            if($ok)
            {
                require_once 'database.php';
                
                if($zdjecieQuery = $mysqli->prepare("SELECT 
                                                            z.id AS id,
                                                            z.id_albumu,
                                                            z.data,
                                                            z.opis,
                                                            z.zaakceptowane,
                                                            a.id AS a_id,
                                                            a.tytul,
                                                            a.id_uzytkownika,
                                                            u.id AS u_id,
                                                            u.login,
                                                            count(o.id_zdjecia) AS l_ocen,
                                                            sum(o.ocena)/count(o.id_zdjecia) AS ocena
                                                        FROM zdjecia z
                                                            join albumy a
                                                                ON a.id = z.id_albumu
                                                            join uzytkownicy u
                                                                ON a.id_uzytkownika = u.id
                                                            left outer join zdjecia_oceny o
                                                                ON o.id_zdjecia = z.id
                                                                    AND o.id_uzytkownika = u.id
                                                        WHERE z.id = ?
                                                        GROUP BY z.id"))
                {
                    require_once 'class.img.php';

                    $zdjecieQuery->bind_param("i", $zdjecie_id);
                    $zdjecieQuery->execute();
                    
                    $result = $zdjecieQuery->get_result();

                    $zdjecie = $result->fetch_assoc();
                    if($zdjecie)
                    {
                        $img = new Image("img/".$zdjecie['id_albumu']."/".$zdjecie['id']);

                        ob_start();
                        $img->Send();
                        $output = base64_encode(ob_get_contents());
                        ob_end_clean();
                        echo    "<section>
                                    <h3>Album: ".$zdjecie['tytul']."</h3>
                                    <h3>Data dodania: ".$zdjecie['data']."</h3>
                                    <h3>Dodał: ".$zdjecie['login']."</h3>
                                    ".($zdjecie['opis'] != "" ? "<p>Opis: ".$zdjecie['opis']."</p>" : "")."
                                </section>";
                        echo    "<section class='zdjecie' zdjecie_id='".$zdjecie['id']."'>
                                    <a href='foto.php?zdjecie_id=".$zdjecie['id']."'>
                                        <img 
                                            src='data:image/jpeg;base64, $output' 
                                            alt='Miniatura zdjęcia w albumie'
                                        >
                                    </a>
                                </section>";
                        echo    "<section>
                                    ".($zdjecie['l_ocen'] != NULL ?
                                    "<h3>Ocena: ".round($zdjecie['ocena'])."</h3>
                                    <h3>Oceniło ".$zdjecie['l_ocen']." użytkowników</h3>"
                                    :
                                    "<h3>To zdjęcie jeszcze nie ma ocen</h3>"
                                    )."
                                </section>";
                    }
                }
                if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
                {
                    $user_id = $_SESSION['user_id'];
                    if($result = $mysqli->query("SELECT 
                                                        o.ocena
                                                    FROM zdjecia_oceny o
                                                        join uzytkownicy u
                                                            ON o.id_uzytkownika = u.id
                                                    WHERE u.id = $user_id
                                                    GROUP BY o.ocena"))
                    {
                        require_once 'class.img.php';
    
                        $ocena = $result->fetch_assoc();
                        if($ocena)
                        {
                            //user już ocenił zdjęcie
                            echo    "<section>
                                        <h3>Już oceniłeś to zdjęcie na ".round($zdjecie['ocena'])."</h3>
                                    </section>";
                        }
                        else
                        {
                            //user jeszcze nie ocenił zdjęcia
                            echo    "<section>
                                        <form id='ocen_form' method='post' action='ocen.php' zdjecie_id='$zdjecie_id'>
                                            <h2>Oceń zdjęcie</h2>
                                            <select id='ocena' name='ocena'>";
                                                for($i = 1; $i<=10; $i++)
                                                    echo "<option value='".$i."'>".$i."</option>";
                            echo            "</select>
                                            <input id='ocena_submit' type='submit' value='Oceń'>
                                        </form>
                                    </section>";
                        }
                    }
                }
            }
            echo "<br><a class='go-album' href='album.php?album_id=$album_id'>Powrót do listy zdjęć</a>";
        ?>
    </main>
    <footer>
        Radosław Kamiński 4Tb
    </footer>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script>
        $("#ocen_form").submit(function(e) 
		{
			e.preventDefault();
			var ocena = $('#ocena').val();
            var zdjecie_id = $(this).attr('zdjecie_id');
            
			$.ajax(
			{
				type: 'POST',
				url: 'ocen.php',
				data: {ocena: ocena, zdjecie_id: zdjecie_id},
				success: function(data) 
				{
					if(data == "no_errors")
                        location.reload();
				}
			});
		});
    </script>
</body>
</html>