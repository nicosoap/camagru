<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/5/2016
 * Time: 2:22 AM
 */
require_once ("config/pdo_connect.php");
if (isset($_GET['login']) && $_GET['login'] != "")
{
    try {
        $stmt = $pdo_connect->prepare("UPDATE users SET verified='1' WHERE login=:user");
        $stmt->bindParam(":user", $_GET['login'], PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    ?>
    <html>
    <head>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta charset="UTF-8">
        <title>Camagru</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
    <div class="container">
        <div class="centered">
            Your email address is now verified. <a href="index.php">Connect now !</a>
        </div>
    </div>
    </body>
    </html>
<?php
}else {
    header("Location: index.php");
}
?>

