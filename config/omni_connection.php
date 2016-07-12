<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 8:55 AM
 */
session_start();
include_once("config/database.php");
try {
    $pdo_connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
