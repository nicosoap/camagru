<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 5:09 AM
 */
include_once ("config/pdo_connect.php");
if ($user->is_logged_in()){
    if ($_POST['webcam'] === "1") {
        $overlayer = $_POST['overlayer'];
        $tmp_img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['userfile']));
        $tmp_name = "uploads/temp/".$_SESSION[user_id].time().".png";
        file_put_contents($tmp_name, $tmp_img);
        if ($camagru->makeCama($tmp_name, $overlayer, $_SESSION['login'])) {
            $image['url'] = $camagru->getURL();
            $image['id'] = $camagru->getID();
            if ($image) {
                echo json_encode($image);
            } else {
                echo json_encode("error");
            }
        } else {
            echo json_encode("error");
        }
    } elseif ($_FILES) {
        $overlayer = $_POST['overlayer'];
        if ($camagru->makeCama($_FILES['userfile']['tmp_name'], $overlayer, $_SESSION['login'])) {
            $image['url'] = $camagru->getURL();
            $image['id'] = $camagru->getID();
            if ($image) {
                echo json_encode($image);
            } else {
                echo json_encode("error");
            }
        } else {
            echo json_encode("error");
        }
    } else {
        echo json_encode("error");
    }
}
?>
