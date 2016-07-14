<?php

/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/2/2016
 * Time: 1:54 PM
 */
class user
{
    private $db;

    public function __construct($pdo_connect)
    {
        $this->db = $pdo_connect;
    }

    public function register($username, $password, $email)
    {
        $verif = FALSE;
        $email_headers = "From: contact@liveoption.io\r\n";
        $email_headers .= "MIME-Version: 1.0\r\n";
        $email_headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
        $email_content = "<html><head><style>body { \nbackground-color: darkgray; color: white; \nfont-family: 'Helvetica', 'Arial', sans-serif; }</style></head><body>\n";
        $email_content .= "<h2>Hi $username,</h2>\n";
        $email_content .= "Thank you for signin'up to CAMAGRU!!! Before you can start\n";
        $email_content .= "pimping your selfies, you needa verify your email address by\n";
        $email_content .= "<a href='http://".$_SERVER['SERVER_NAME']."/verify.php?login=$username'>\ngoing here</a> or copy-paste that link to your\n";
        $email_content .= "browser :<br />\n";
        $email_content .= "http://".$_SERVER['SERVER_NAME']."/verify.php?login=$username\n";
        $email_content .= "</body></html>";

        if (!(mail($email, "Verify your CAMAGRU account", $email_content, $email_headers))) {
            $verif = TRUE;
        }
        try {
            $stmt = $this->db->prepare("INSERT INTO users(login, email, password, verified) VALUES(:username, :email, :password, :verif)");
            $stmt->bindparam(":username", $username, PDO::PARAM_STR);
            $stmt->bindparam(":password", $password, PDO::PARAM_STR);
            $stmt->bindparam(":email", $email, PDO::PARAM_STR);
            $stmt->bindparam(":verif", $verif, PDO::PARAM_BOOL);
            $stmt->execute();
        return $stmt;
        } catch (PDOException $e) {
            echo 'Registration failed: ' . $e->getMessage();
        }
    }

    public function login($username, $userpasswd, $save) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE login=:username OR email=:useremail LIMIT 1");
            $stmt->execute(array(':username' => $username, ':useremail' => $username));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                if (hash("whirlpool", $userpasswd) === $userRow['password'] && ($userRow['verified'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login'] = $username;
                    $_SESSION['user_id'] = $userRow['user_id'];
                    if ($save == true) {
                        setcookie('login', $username);
                        setcookie('passwd', $userpasswd);
                    }
                    return true;
                } else {
                        return false;
                }
            }
        }
        catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
        }
    }

    public function is_verified($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE login=:username OR email=:useremail LIMIT 1");
            $stmt->execute(array(':username' => $username, ':useremail' => $username));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                if (hash("whirlpool", $password) === $userRow['password']) {
                    if ($userRow['verified'] == "1") {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function reset_password($email){
        try {
            $token = md5($email.time());
            $stmt = $this->db->prepare("INSERT INTO tokens(user_id, usag, token) SELECT user_id, 'passwd', :token FROM users WHERE email =:email LIMIT 1");
            $stmt->execute(array(':email'=>$email, ':token' => $token));
            $email_headers = "From: contact@liveoption.io\r\n";
            $email_headers .= "MIME-Version: 1.0\r\n";
            $email_headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
            $email_content = "<html><head><style>body { \nbackground-color: darkgray; color: white; \nfont-family: 'Helvetica', 'Arial', sans-serif; }</style></head><body>\n";
            $email_content .= "<h2>Hello,</h2>\n";
            $email_content .= "This message was sent to you because a new password was requested\n";
            $email_content .= "for your account on CAMAGRU !!! \n<a href='http://".$_SERVER['SERVER_NAME']."/forgot.php?forgot=$token'>\nIf you requested a new password, click here</a>\n";
            $email_content .= "or copy-paste the following link to your favorite browser:\n";
            $email_content .= "http://".$_SERVER['SERVER_NAME']."/forgot.php?$token\n";
            $email_content .= "If you didn't request a new password, please disregard this message.\n";
            $email_content .= "<br /><br /><br /><br /><br /><br /><br />";
            $email_content .= "</body></html>";
            if ($stmt){
                mail($email, "Change your CAMAGRU password", $email_content, $email_headers);
                return 1;
            } else {
                return 0;
            }
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function change_password($password, $token) {
        try {
            $stmt = $this->db->prepare("UPDATE users RIGHT JOIN tokens ON users.user_id=tokens.user_id SET users.password=:passwd, tokens.status='0' WHERE tokens.usag='passwd' AND tokens.token=:token");
            $stmt->execute(array(':passwd' => $password, ':token' => $token));
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function is_logged_in()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != "") {
            return true;
        } else { return false; }
    }

    public function redirect($url)
    {
        header('Location: '.$url);
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['logged_in']);
        return true;
    }
}
