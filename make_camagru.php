<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 5:09 AM
 */
include_once("header.php");
if (!$user->is_logged_in()){
    header("Location: index.php");
}
if ($camagru->makeCama($_FILES['userfile']['tmp_name'], "img/overlayers/test-image.png", $_SESSION['login'])) {
    $image_src = $camagru->getURL();
    if ($image_src != 0) {
        echo '<img src="$image_src">';
    }
} else {
    echo "error".print_r($image_src);
}
include_once("footer.php");
?>
