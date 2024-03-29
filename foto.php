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
        <?php
            function filter($get_name) {return htmlspecialchars(filter_input(INPUT_GET, $get_name), ENT_QUOTES, 'UTF-8');}

            $album_id = filter("album_id");
            $zdjecie_id = filter("zdjecie_id");
            
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
                                                    WHERE z.id = ?
                                                        GROUP BY z.id"))
                {

                    $zdjecieQuery->bind_param("i", $zdjecie_id);
                    $zdjecieQuery->execute();
                    
                    $result = $zdjecieQuery->get_result();

                    $zdjecie = $result->fetch_assoc();

                    /*==============zdjęcie i ocena===============*/
                    if($zdjecie)
                    {
                        if($zdjecie['zaakceptowane'] == 1)
                        {
                            require_once 'class.img.php';
                            $img = new Image("img/".$zdjecie['id_albumu']."/".$zdjecie['id']);
    
                            ob_start();
                            $img->Send();
                            $output = base64_encode(ob_get_contents());
                            ob_end_clean();
                            ?>
        <div id="header_text_wrapper">
                    <h1><?=$zdjecie['tytul']?></h1>
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
                            echo "<a class='go-album' href='album.php?album_id=$album_id'>Powrót do listy zdjęć</a><br>";
                            
                            echo    "<section id='informacje'>
                                        <h4>Dodane przez ".$zdjecie['login']."</h4>
                                        <span>".$zdjecie['data']."</span>
                                        ".($zdjecie['opis'] != "" ? "<p>".$zdjecie['opis']."</p>" : "")."
                                    </section>";
                            echo    "<section id='zdjecie' zdjecie_id='".$zdjecie['id']."'>";

                            if($zdjeciaQuery = $mysqli->prepare("SELECT id FROM zdjecia WHERE id_albumu = ? AND zaakceptowane = 1 ORDER BY id DESC"))//od najnowszych
                            {
                                $zdjeciaQuery->bind_param("i", $album_id);
                                $zdjeciaQuery->execute();
                                $result = $zdjeciaQuery->get_result();
                                $zdjecia_result = $result->fetch_all();
                                $zdjecia = array_map('current', $zdjecia_result);//zamiana dwu wymiarowej tablicy wyniku zapytania w jedno wymiarową
                                $aktualny = array_search($zdjecie_id, $zdjecia);//klucz id aktualnego zdjecia w tablicy
                                if($aktualny > 0)
                                    echo "<a class='strzalka w-lewo' href='?album_id=$album_id&zdjecie_id=".$zdjecia[$aktualny-1]."'></a>";//coraz nowsze zdjęcia
                                if($aktualny < count($zdjecia)-1)
                                    echo "<a class='strzalka w-prawo' href='?album_id=$album_id&zdjecie_id=".$zdjecia[$aktualny+1]."'></a>";//coraz starsze zdjęcia
                            }

                            echo        "<img 
                                            src='data:image/jpeg;base64, $output' 
                                            alt='Zdjęcie w albumie'
                                        >
                                    </section>";
                            echo    "<section>
                                        ".($zdjecie['l_ocen'] != NULL ?
                                        "<h3>Ocena: ".round($zdjecie['ocena'])."</h3>
                                        <h3>Oceniło ".$zdjecie['l_ocen']." użytkowników</h3>"
                                        :
                                        "<h3>To zdjęcie jeszcze nie ma ocen</h3>"
                                        )."
                                    </section>";
                            
                            /*==============formularz dodawania oceny===============*/
                            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
                            {
                                $user_id = $_SESSION['user_id'];
                                if($result = $mysqli->query("SELECT o.ocena
                                                            FROM zdjecia_oceny o
                                                            WHERE o.id_uzytkownika = $user_id
                                                                AND o.id_zdjecia = $zdjecie_id"))
                                {
                                    $ocena = $result->fetch_assoc();
                                    if($ocena)
                                    {
                                        //user już ocenił zdjęcie
                                        echo    "<section>
                                                    <h3 id='juz_oceniles_info'>Już oceniłeś to zdjęcie na ".round($ocena['ocena'])."</h3>
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
                            echo                "<section id='komentarze'></section>";

                            /*==============formularz dodawania komentarza===============*/
                            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
                            {
                                echo            "<section>
                                                    <form id='kom_form' method='post' action='dodaj_kom.php' zdjecie_id='$zdjecie_id'>
                                                        <textarea id='kom_tresc' placeholder='Dodaj komentarz...'></textarea>
                                                        <input id='kom_submit' type='submit' value='Dodaj'>
                                                    </form>
                                                </section>";
                            }
                            else
                            {
                                echo            "<section>
                                                    <h3>Musisz się zalogować, aby dodać komentarz</h3>
                                                </section>";
                            }
                        }
                        else
                            echo    "<section><h2>To zdjęcie nie zostało jeszcze zaakceptowane, więc nie można go tutaj zobaczyć. Aby obejrzeć wszystkie SWOJE zdjęcia, przejdź do strony 'Dodaj zdjęcie'.</h2></section>";
                    
                    }
                    else
                        echo    "<section><h2>Zdjęcie o podanym id nie istnieje.</h2></section>";

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
    
		function pokaz_kom()
		{
            var zdjecie_id = $("#zdjecie").attr('zdjecie_id');
            if(zdjecie_id)
            {
                $.ajax(
                {
                    type: 'POST',
                    url: 'pokaz_kom.php',
                    data: {zdjecie_id: zdjecie_id},
                    success: function(data) 
                    {
                        $("#komentarze").html(data);
                    }
                });
            }
		}

		$(window).on("load", function()
		{
            pokaz_kom();
		});

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
        
        $("#kom_form").submit(function(e) 
		{
			e.preventDefault();
			var komentarz = $('#kom_tresc').val();
            var zdjecie_id = $("#zdjecie").attr('zdjecie_id');
			$.ajax(
			{
				type: 'POST',
				url: 'dodaj_kom.php',
				data: {komentarz: komentarz, zdjecie_id: zdjecie_id},
				success: function(data) 
				{
					if(data == "no_errors")
					{
						$('#kom_form')[0].reset();
						pokaz_kom();
					}
                    else
                        $("main").append(data);
				}
			});
		});
    </script>
</body>
</html>