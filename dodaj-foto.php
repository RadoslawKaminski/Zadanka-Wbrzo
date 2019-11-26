<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true)
{
	header("Location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Dodaj zdjęcie | Projekt aplikacji webowej</title>
    <meta name="description" content="Strona dodawania zdjęcia">
    <meta name="keywords" content="">

    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/dodaj-foto.css" type="text/css">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <header>
        <div id="header_text_wrapper">
    		<h1>Dodaj zdjęcie</h1>
        </div>
		<nav>
			<ul>
				<li><a href="galeria.php">Galeria</a></li>
				<li><a href="dodaj-album.php">Załóź album</a></li>
				<li><a href="dodaj-foto.php">Dodaj zdjęcie</a></li>
				<li><a href="top-foto.php">Najlepiej oceniane</a></li>
				<li><a href="nowe-foto.php">Najnowsze</a></li>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="konto.php">Moje konto</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) echo '<li><a href="wyloguj.php">Wyloguj się</a></li>'?>
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['uprawnienia']) && ($_SESSION['uprawnienia'] == 'moderator' || $_SESSION['uprawnienia'] == 'administrator')) echo '<li><a href="admin/index.php">Panel administracyjny</a></li>'?>
			</ul>
		</nav>
    </header>
    <main>
		<nav id="albumy">
			<h3>Albumy</h3>
			<ul>
			<?php
				require_once 'database.php';
				if($albumQuery = $mysqli->prepare('SELECT id, tytul, data, id_uzytkownika FROM albumy WHERE id_uzytkownika = ?'))
				{
					$albumQuery->bind_param("i", $_SESSION['user_id']);
					$albumQuery->execute();
					
					$result = $albumQuery->get_result();
					$albumQuery->close();

					while($album = $result->fetch_assoc())
					{
						echo "<li class='album ".(isset($_SESSION['selected_album']) ? ($_SESSION['selected_album'] == $album['id'] ? "current" : "") : "")."' album_id='".$album['id']."'>".$album['tytul']."</li>";
					}
				}
			?>
			</ul>
		</nav>
		<section>
			<h3 style="display: none" class='error'></h3>
			<div class="form-wrapper">
				<form id="foto_form" action="dodawanie_zdjecia.php" method="post">
					<span style="display: none" class='error'></span>
					<input class="file-input" type="file" name="file-input" required><br>
					<textarea class="opis" maxlength="255" placeholder="Dodaj opis..."></textarea>
					<input class="dodaj-album" name="dodaj-album" type="submit" value="Dodaj album">
				</form>
			</div>
		</section>
    </main>
    <footer>
        Radosław Kamiński 4Tb
	</footer>
	<script src="js/jquery-3.4.1.min.js"></script>
	<script> 
		$("#albumy li.album").on("click", function()
		{
			$("#albumy li.album").removeClass("current");
			$(this).addClass("current");
		});
		$("#foto_form").submit(function(e) 
		{
			e.preventDefault();
			$('.error').css('display', 'none');
			if(!$("#albumy li.album.current").length)
			{
				$("h3.error").css('display', 'block').text("Wybierz album, do którego chcesz dodać zdjęcie");
				return
			}
			if($(this).find('.file-input').val() == "")
			{
				$("#foto_form .error").css('display', 'block').text("Wybierz zdjęcie, które chcesz dodać");
				return
			}
			var formData = new FormData();
			formData.append("file", $(this).find('.file-input')[0].files[0]);
			formData.append("album_id", $("#albumy li.album.current").attr("album_id"));
			formData.append("opis", $("textarea.opis").val());
			$.ajax(
			{
				type: 'POST',
				url: 'dodawanie_zdjecia.php',
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data) 
				{
					if(data == "no_errors")
					{
						
					}
					else
					{
						$('#foto_form .error').css('display', 'block').html(data);
					}
				}
			});
		});
	</script>
</body>
</html>