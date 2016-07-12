<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/5/2016
 * Time: 8:12 AM
 */
include_once("config/pdo_connect.php");
$user->logout();
$user->redirect("index.php");
?>
