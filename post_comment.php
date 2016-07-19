<?php
/**
 * Created by PhpStorm.
 * User: opichou
 * Date: 7/18/16
 * Time: 9:04 PM
 */
include_once('config/pdo_connect.php');
if (isset($_POST['photo_id']) && isset($_POST['user_id']) && $_POST['photo_id'] != "" && $_POST['user_id'] != "" && isset($_POST['comment_content']) && $_POST['comment_content'] != "") {
    $photo_id = $_POST['photo_id'];
    $user_id = $_POST['user_id'];
    $content = $_POST['comment_content'];
    $test = $camagru->postComment($user_id, $photo_id, $content);
    if ($test == 1) {
        echo 1;
    } else {
        echo 0;
    }
}?>