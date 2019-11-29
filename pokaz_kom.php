<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
    $zdjecie_id=filter('zdjecie_id');
    if(!ctype_digit($zdjecie_id))
        exit;

    require_once 'database.php';
    
    if($komQuery = $mysqli->prepare("SELECT k.data, k.komentarz, k.zaakceptowany, u.login
                                        FROM zdjecia_komentarze k 
                                            join uzytkownicy u 
                                                on u.id = k.id_uzytkownika
                                        WHERE k.id_zdjecia = ? 
                                            AND k.zaakceptowany = 1
                                        ORDER BY k.data DESC"))
    {
        $komQuery->bind_param("i", $zdjecie_id);
        $komQuery->execute();
        $result = $komQuery->get_result();
        if($result->num_rows > 0)
        {
            while(($kom = $result->fetch_assoc()))
            {
                if($kom['zaakceptowany'] == 1)
                    echo    "<article class='komentarz'>
                                <h4>".$kom['login']."</h4>
                                <span>".$kom['data']."</span>
                                <p>".$kom['komentarz']."</p>
                            </article>";
            }
        }
        else
            echo            "<h3>Jeszcze nie ma żadnego komentarza</h3>";
    }
}
else
{
	//to nie prośba o pokazanie komentarzy
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
		header("Location: galeria.php");
	else
		header("Location: index.php");
}