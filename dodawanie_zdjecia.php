<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
	$album_id=filter('album_id');
	$opis=nl2br(filter('opis'));
	
    $_SESSION['error'] = "";
    //walidacja danych
    if(!ctype_digit($album_id))
		$_SESSION['error'] = "Wybrano niepoprawny album<br>";
    if(!getimagesize($_FILES["file"]['tmp_name'])) 
		$_SESSION['error'] .= "Przekazany plik to nie zdjęcie<br>";
	if(strlen($opis) > 255)
		$_SESSION['error'] .= "Opis może zawierać maksymalnie 255 znaków<br>";
	//////////////////

	require_once 'database.php';
    
    //sprawdzenie czy album istnieje i czy należy do użytkownika
    if($isUserAlbumQuery = $mysqli->prepare('SELECT id, id_uzytkownika FROM albumy WHERE id = ?'))
    {
        $isUserAlbumQuery->bind_param("i", $album_id);
        $isUserAlbumQuery->execute();
        
        $result = $isUserAlbumQuery->get_result();
        $album = $result->fetch_assoc();
        $isUserAlbumQuery->close();

        if($album)
        {
            if($album["id_uzytkownika"] != $_SESSION["user_id"])
                $_SESSION['error'] .= "Ten album nie należy do Ciebie!<br>";
        }
        else
            $_SESSION['error'] .= "Ten album nie istnieje<br>";
    }
    else
        $_SESSION['error'] = "Wystąpił błąd, spróbuj ponownie później.";
    /////////////////////////////////////////////


	if(empty($_SESSION['error']))
	{
		if($addphoto = $mysqli->prepare("INSERT INTO zdjecia(id, opis, id_albumu, data, zaakceptowane) VALUES (NULL, ?, ?, NOW(), 0)"))
		{
			$addphoto->bind_param("si", $opis, $album_id);
			$addphoto->execute();
			$addphoto->close();
            $photo_id = $mysqli->insert_id;
			if($result = $mysqli->query("SELECT id, id_albumu FROM zdjecia WHERE id = $photo_id"))
			{
				$photo = $result->fetch_assoc();
				if($photo)
				{
                    //dodawanie zdjęcia na serwer
                    if (!move_uploaded_file($_FILES["file"]['tmp_name'], "img/".$album_id."/".$photo_id))
                        echo "Nieudane przekazanie pliku";
					else
					{
						//skalowanie zdjęcia
						require_once 'class.img.php';
						$img = new Image("img/".$album_id."/".$photo_id);
						$img->SetMaxSize(1200);
						$img->Save("img/".$album_id."/".$photo_id);
						////////////////////

						echo "no_errors";
					}
                    //////////////////////////////

					$mysqli->close();
					exit;
				}
            }
            
			$_SESSION['error'] = "Wystąpił błąd, spróbuj ponownie później.";
			$mysqli->close();
			exit;
		}
		else
		{
			//błąd dodawania po stronie serwera
			$_SESSION['error'] = "Wystąpił błąd, spróbuj ponownie później.";
		}
	}
	else
	{
        //wystąpiły błędy
        echo $_SESSION['error'];
	}

	$mysqli->close();
}
else
{
	//to nie prośba o dodanie zdjęcia
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
	{
		header("Location: galeria.php");
	}
	else
	{
		header("Location: index.php");
	}
}