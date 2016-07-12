<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 03:07
 */
include_once("config/omni_connection.php");
include_once("class/class.user.php");
include_once("class/class.camagru.php");
$user = new user($pdo_connect);
$camagru = new camagru($pdo_connect);
?>
