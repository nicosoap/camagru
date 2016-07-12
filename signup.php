<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 03:03
 */

include_once("config/pdo_connect.php");
include("header.php");
if ($user->is_logged_in()){
    $user->redirect('home.php');
}
function check_username($username, $email){
    include("config/database.php");
    try {
        $check_connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $check_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $check = $check_connect->prepare("SELECT * FROM users WHERE login =:username OR email=:email");
        $check->bindParam(":username", $username, PDO::PARAM_STR);
        $check->bindParam(":email", $email, PDO::PARAM_STR);
        $check->execute();
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    if ($check->rowCount() > 0) {
        return 0;
    } else {
        return 1;
    }
}
if (isset($_POST["submit"]) && $_POST['submit'] == 'SUBMIT!') {

    $password = hash("whirlpool", $_POST["passwd1"]);
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    if ($_POST["passwd1"] != $_POST["passwd2"]) {
        $error[] = "PASSWORD WAS NOT THE SAME IN BOTH FIELDS !";
    }else if($login=="") {
        $error[] = "PROVIDE LOGIN !";
    }
    else if($email=="") {
        $error[] = "PROVIDE EMAIL !";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'PROVIDE A VALID EMAIL ADDRESS !';
    }
    else if($_POST['passwd1']=="") {
        $error[] = "PROVIDE PASSWORD !";
    }
    else if(strlen($_POST['passwd1']) < 6){
        $error[] = "PASSWORD MUST BE AT LEAST 6 CHARACTERS !";
    }
    else if (check_username($login, $email) == 0) {
        $error[] = "LOGIN OR EMAIL ALREADY TAKEN !";
    }
    else
    {
        if ($user->register($login, $password, $email))
        {
            $user->redirect("signup.php?joined");
        }
    }
} ?>
        <div class="centered">
            <form title="subscribe to Camagru!!!"
                  about="fill this form to subscribe to Camagru!!! and start pimping your selfies" name="signup"
                  method="post" action="signup.php">
                <?php if (isset($error))
                {
                    foreach($error as $alert)
                    {
                        ?>
                <div class="form alert"><?php echo $alert; ?></div>
                <?php
                    }
                } else if (isset($_GET['joined']))
                {
                    ?>
                    <a href="index.php"><div class="form green">THANK YOU FOR JOINING CAMAGRU !!!<br />CHECK YOUR EMAILS TO VALIDATE SUBSCRIPTION AND LOGIN</div></a>
                <?php
                } else {?>
                <div class="form">FILL THIS FORM AND START PIMPING YOUR SELFIES!!!</div>
                <?php } ?>
                <input type="text" name="login" placeholder="Login :" required/>
                <input type="email" name="email" placeholder="Email :" required/>
                <input type="password" name="passwd1" placeholder="Password :"required/>
                <input type="password" name="passwd2" placeholder="Again :"required/>
                <input type="submit" name="submit" value="SUBMIT!"/>
            </form>
        </div>
        <?php
        include("footer.php");
?>
