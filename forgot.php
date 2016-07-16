<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/5/2016
 * Time: 8:26 AM
 */
include_once("config/pdo_connect.php");
if (isset($_GET['forgot']) && $_GET['forgot']!= "") {
    include_once("header.php");
    ?>
<div class="centered form-container">
    <form name="change_password" method="post" action="forgot.php">
        <div class="form">CHOOSE A NEW PASSWORD</div>
        <?php if (isset($error)) { ?>
            <div class="form alert"><?php echo $error; ?></div>
        <?php } ?>
        <input type="hidden" name="user_id" value="<?php echo $_GET['forgot']; ?>" />
        <input type="password" name="passwd1" placeholder="Password:" required />
        <input type="password" name="passwd2" placeholder="Password:" required />
        <input type="submit" name="submit" value="CHANGE PASSWORD !!!">
    </form>
</div>
<?php
} elseif (isset($_POST['submit']) && $_POST['submit']=="CHANGE PASSWORD !!!") {
    $password = $_POST['passwd1'];
    $user_id = $_POST['user_id'];
    if ($_POST['passwd1'] !== $_POST['passwd2']) {
        $error = "PLEASE CHACK BOTH PASSWORD ARE IDENTICAL.";
    }elseif($password=="") {
        $error = "PROVIDE PASSWORD !";
    }
    else if(strlen($password) < 6){
        $error = "PASSWORD MUST BE AT LEAST 6 CHARACTERS !";
    } else {
        $user->redirect("signin.php");
    }
    include_once("header.php");
    ?>
    <div class="centered form-container">
    <form name="change_password" method="post" action="forgot.php">
        <div class="form">CHOOSE A NEW PASSWORD</div>
        <?php if (isset($error)) { ?>
        <div class="form alert"><?php echo $error; ?></div>
    <?php } ?>
    <input type="password" name="passwd1" placeholder="Password:" required />
    <input type="password" name="passwd2" placeholder="Password:" required />
    <input type="submit" name="submit" value="CHANGE PASSWORD !!!">
    </form>
    </div>
    <?php
} else {
    if (isset($_POST['submit']) && $_POST['submit']== "SEND EMAIL !!!"){
        if ($user->reset_password($_POST['email'])) {
            $alert = "PLEASE CHECK YOUR EMAILS !";
        } else {
            $error = "INCORRECT EMAIL ADDRESS !";
        }
    }
    include_once("header.php");
?>
<div class="centered form-container">
    <form name="change_password" method="post" action="forgot.php">
        <div class="form">LET'S HAVE A NEW PASSWORD. SHALL-WE ?</div>
        <?php if (isset($error)) { ?>
            <div class="form error"><?php echo $error; ?></div>
        <?php } elseif (isset($alert)) { ?>
            <div class="form alert"><?php echo $alert; ?></div>
        <?php } ?>
        <input type="email" name="email" placeholder="Email :" required/>
        <input type="submit" name="submit" value="SEND EMAIL !!!">
    </form>
</div>
<?php
}
include("footer.php");
?>
