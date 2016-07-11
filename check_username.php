<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/5/2016
 * Time: 2:43 AM
 */
function check_username($username){
    require_once ("config/pdo_connect.php");
    try {
        $check = $pdo_connect->prepare("SELECT * FROM users WHERE login =:username");
        $check->bindParam(":username", $username, PDO::PARAM_STR);
        $check->execute();
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    if ($check->rowCount() != 0) {
        return (0);
    } else {
        return (1);
    }
}
if (isset($_GET['login']) && $_GET['login'] != "") {
    if (check_username($_GET['login'])) {
        echo 1;
        return;
    }}
echo 0;
return;
?>
