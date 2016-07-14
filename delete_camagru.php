<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 5:09 AM
 */
include_once ("config/pdo_connect.php");
if ($user->is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    $photo_id = $_GET['id'];
    if ($camagru->deletePhoto($photo_id, $user_id)) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    echo "0";
}
?>
