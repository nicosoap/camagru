<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 05:16
 */
include_once("config/pdo_connect.php");
    if ($user->is_logged_in()!="") {
        $user->redirect("home.php");
    } else if (isset($_POST['submit'])) {
        $username = $_POST['login'];
        $userpasswd = $_POST['passwd'];
        $save = $_POST['save'];

        if ($user->login($username, $userpasswd, $save) && ($user->is_verified($username, $userpasswd))) {
            $user->redirect("home.php");
        } else if (!($user->is_verified($username, $userpasswd))) {
            $error = "Check for your confirmation email !";
        } else {
            $error = "Try again !";
        }
    }
include("header.php");
?>
<div class="centered form-container">
<form name="login" method="post" action="signin.php">
    <div class="form">YOU KNOW WHAT TO DO</div>
    <?php if (isset($error)) { ?>
    <div class="form alert"><?php echo $error; ?></div>
    <?php } ?>
    <input type="text" name="login" placeholder="Login:" <?php if (isset($_COOKIE['login'])) { echo 'value="'.$_COOKIE['login'].'"'; } ?> required />
    <input type="password" name="passwd" placeholder="Password:" <?php if (isset($_COOKIE['passwd'])) { echo 'value="'.$_COOKIE['passwd'].'"'; } ?>required />
    <input type="submit" name="submit" value="CONNECT !!!">
    <input type="checkbox" name="save" placeholder="remember me" value="true"/>
    <a href="forgot.php"><span>forgot password</span></a>
    <a href="signup.php">Sign up !</a>
</form>
</div>
<?php
include("footer.php");
?>
