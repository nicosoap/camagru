<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 02:36
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
<div class="contener">

    <div class="header">
        <span id="title"><h1>CAMAGRU!!!</h1></span>
        <span class="img" ><a href="gallery.php"><img src="img/random-icon.png"/></a> </span>
        <span class="img" ><a href="home.php" ><img src="img/camera2-icon.png"/></a></span>
        <?php if ($user->is_logged_in()) { ?> <span class="stick-right"><a href="logout.php">LOGOUT</a> </span> <?php } ?>
    </div>
