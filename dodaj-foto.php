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
						echo "<li class='".($_SESSION['selected_album'] == $album['id'] ? "current" : "")."' album_id='".$album['id']."'>".$album['tytul']."</li>";
					}
				}
			?>
			</ul>
		</nav>
		<section>
			<h3 style="display: none" class='error'></h3>
			<div class="form-wrapper">
				<form id="foto_form" action="dodaj-foto.php" method="post">
					
					<input type="file" name=""><br>
					<span style="display: none" class='error'></span>
					<input class="dodaj-album" name="dodaj-album" type="submit" value="Dodaj album">
				</form>
			</div>
		</section>
    </main>
    <footer>
        Radosław Kamiński 4Tb
	</footer>
	<script>
			$("#foto-form").submit(function() 
			{
				var formData = new FormData();
				let file_n = $(this).find('.file-input')[0].files[0]['name'].replace(/\s/g, "-");
				formData.append(file_n, $(this).find('.file-input')[0].files[0]);
				$.ajax(
				{
					type: 'POST',
					url: 'dodaj-foto.php',
					data: formData,
					processData: false,  // tell jQuery not to process the data
					contentType: false,  // tell jQuery not to set contentType
					success: function(data) 
					{
						if(data == "no_errors")
						{
							
						}
						else
						{
							$('#errors').html(data);
						}
					}
				});
			}
	</script>
</body>
</html>