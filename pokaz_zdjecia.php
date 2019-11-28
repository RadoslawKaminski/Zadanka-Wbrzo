
<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	function filter($post_name) {return htmlspecialchars(filter_input(INPUT_POST, $post_name), ENT_QUOTES, 'UTF-8');}
    $album_id=filter('album_id');
    if(!ctype_digit($album_id))
        exit;

    $files = array();
    if($d = opendir("img/".$album_id))
    {
        while (false !== ($entry = readdir($d)))
        {	
            if($entry != '.' && $entry != '..')
                $files[] = $entry;
        }
        closedir($d);

        require_once 'class.img.php';
        foreach(array_reverse($files) as $file) {
            $img = new Image("img/".$album_id."/".$file);
            $img->SetMaxSize(180);

            ob_start();
            $img->Send();
            $output = base64_encode(ob_get_contents());
            ob_end_clean();

            echo '<img src="data:image/jpeg;base64, '.$output.'" style="display:inline-block;margin:10px;">';
        }
    }
}
else
{
	//to nie prośba o pokazanie zdjęć
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
		header("Location: galeria.php");
	else
		header("Location: index.php");
}