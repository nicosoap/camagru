<?php
/**
 * Created by PhpStorm.
 * User: opichou
 * Date: 7/14/16
 * Time: 3:28 PM
 */
include_once("config/pdo_connect.php");
if (!$user->is_logged_in()) {
    $user->redirect("signin.php");
}
include("header.php");
include("image_gal.php");
?><script src="js/gallery.js"></script><?php
include("footer.php");
?>