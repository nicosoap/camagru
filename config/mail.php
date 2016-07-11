<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/4/2016
 * Time: 11:56 PM
 */
$email_headers = "From: contact@liveoption.io\r\n";
$email_headers .= "MIME-Version: 1.0\r\n";
$email_headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
$email_content = "<html><body>\n";
$email_content .= "<h2>Hi $username,</h2>\n";
$email_content .= "Thank you for signin'up to CAMAGRU!!! Before you can start\n";
$email_content .= "pimping your selfies, you needa verify your email address by\n";
$email_content .= "<a href='http://".$_SERVER['SERVER_NAME']."/verify.php?login=$username'>\ngoing here</a> or copy-paste that link to your\n";
$email_content .= "browser :<br />\n";
$email_content .= "http://".$_SERVER['SERVER_NAME']."/verify.php?login=$username\n";
$email_content .= "</body></html>";

if (mail("opichou@student.42.fr", "Verify your CAMAGRU account", $email_content, $email_headers)){
    echo "ok!";
}

?>
