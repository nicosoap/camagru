<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/3/2016
 * Time: 4:13 AM
 */
function auth()
{
    if (isset($_SESSION['logged_in']) && $_SESSION['login'] != "") {
        return true;
    } else {
        return false;
    }
}
