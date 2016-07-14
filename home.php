<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 02:36
 */
include_once("config/pdo_connect.php");
if (!$user->is_logged_in()) {
    $user->redirect("signin.php");
}
include("header.php");
include("video.php");
include("sidebar.php");
?><script src="js/video.js"></script><?php
include("footer.php");
?>

