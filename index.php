<?php
include_once("config/pdo_connect.php");
if ($user->is_logged_in()!="") {
    $user->redirect("home.php");
} else {
    $user->redirect("signin.php");
}
?>
