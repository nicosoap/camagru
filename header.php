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
        <span class="img" ><a href="#"><img src="img/random-icon.png"/></a> </span>
        <span class="img" ><a href="#" ><img src="img/camera2-icon.png"/></a></span>
        <span class="stick-right"><?php if (isset($_SESSION["loggued_in"]) && $_SESSION["loggued_in"] != "") {?>
            <a href="login.php"><?php echo $_SESSION["name"]; ?></a> / <a href="signup.php">SIGN-OUT</a></span>
        <?php } else {?>
            <a href="signin.php">LOG-IN</a> / <a href="signup.php">SIGN-UP</a></span>
        <?php } ?>

    </div>
