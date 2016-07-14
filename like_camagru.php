<?php
/**
 * Created by PhpStorm.
 * User: opichou
 * Date: 7/14/16
 * Time: 4:13 PM
 */
include_once ("config/pdo_connect.php");
if ($user->is_logged_in() && ($_POST['user_id'] === $_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $photo_id = $_POST['photo_id'];
    if ($camagru->likePhoto($user_id, $photo_id)) {
        echo 1;
    } else { echo 0; }
}
?>