<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 03:07
 */
require_once("config/database.php");
try {
    $pdo_connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>