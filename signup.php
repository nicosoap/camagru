<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 03:03
 */
session_start();

if (isset($_POST["submit"]) && $_POST['submit'] == 'SUBMIT!')
{
    if ($_POST["passwd1"] === $_POST["passwd2"])
    {
        $password = hash("whirlpool", $_POST["passwd1"]);
        $login = $_POST['login'];
        $email = $_POST['email'];

        include("config/pdo_connect.php");
        try {
            $stmt = $pdo_connect->prepare("INSERT INTO user ( login, password, email )
                  VALUES (:login, :password, :email);");
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            header("Location: error.html");
        }
        $pdo_connect = Null;
        try {
            $pdo_connect = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        $token = md5($login.time());
        try {
            $token_query = $pdo_connect->prepare("INSERT INTO tokens (user_id, token, usag) SELECT (user_id, :token, 'signup') FROM user WHERE login =:login");
            $token_query->bindParam(":token", $token, PDO::PARAM_STR);
            $token_query->bindParam(":login", $login, PDO::PARAM_STR);
            $token_query->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: '. $e->getMessage();
            header("Location :error.html");
        }
        $email_headers = "From: contact@liveoption.io\r\n";
        $email_headers .= "MIME-Version: 1.0\r\n";
        $email_headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";

        $email_content = "<html><body>";
        $email_content .= "<h1>Welcome to CAMAGRU!!!</h1>\r\n";
        $email_content .= "<h2>Hi $login,</h2>\r\n";
        $email_content .= "Thank you for signin'up to CAMAGRU!!! Before you can start\r\n";
        $email_content .= "pimping your selfies, you needa verify your email address by\r\n";
        $email_content .= "<a href='localhost/camagru/verify.php?token_id=$token'>going here</a> or copy-paste that link to your\r\n";
        $email_content .= "browser :\r\n";
        $email_content .= "localhost/camagru/verify.php?token_id=$token\r\n";
        $email_content .= "</body></html>";

        /**
        if ( mail($email, "Verify your CAMAGRU account", "test" )) {
            $pdo_connect= Null;
            header("Location: index.php");
        } else {

            print_r(error_get_last());*/

        header ("Location: index.php");

    }
    
} else {
    include("header.php");
    ?>
    <div class="centered">
        <form title="subscribe to Camagru!!!" about="fill this form to subscribe to Camagru!!! and start pimping your selfies" name="signup" method="post" action="signup.php">
            <div class="form">FILL THIS FORM AND START PIMPING YOUR SELFIES!!!</div>
            <input type="text" name="login" placeholder="Login :"/>
            <input type="email" name="email" placeholder="Email :"/>
            <input type="password" name="passwd1" placeholder="Password :"/>
            <input type="password" name="passwd2" placeholder="Again :"/>
            <input type="submit" name="submit" value="SUBMIT!" />
        </form>
    </div>
    <?php
    include("footer.php");
     }
?>