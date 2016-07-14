<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/8/2016
 * Time: 5:09 AM
 */
include_once ("config/pdo_connect.php");
if ($user->is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    if ($_POST['perso'] === "1") {
        if ($dataset = $camagru->getMyPhotos($user_id)) {
            echo json_encode($dataset);
        } else {  echo "error"; }
    } else {
        if (isset($_POST['page']) && ($_POST['page'] != "")) {
            $page = $_POST['page'];
        } else {
            $page = 1;
        }
        if ($dataset = $camagru->getAllPhotos($user_id, $page)) {
            echo json_encode($dataset);
        } else { echo "error"; }
    }
}
?>
