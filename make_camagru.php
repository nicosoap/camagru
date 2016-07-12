<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 5:09 AM
 */
include_once ("config/pdo_connect.php");
if (!$user->is_logged_in()){
    header("Location: index.php");
}
if ($camagru->makeCama($_FILES['userfile']['tmp_name'], "img/overlayers/test-image.png", $_SESSION['login'])) {
    $image['url'] = $camagru->getURL();
    $image['id'] = $camagru->getID();
    if ($image) {
        echo json_encode($image);
    }else {
        echo "error";
    }
} else {
    echo "error";
}
?>
