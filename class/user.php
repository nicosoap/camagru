<?php

/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/2/2016
 * Time: 1:54 PM
 */
class user
{
    public $login = null;
    private $password = null;
    private $email = null;

    public function __construct( $data = array() ) {
        if ( isset($data['login'])) $this->login = stripslashes(strip_tags( $data['username']));
        if( isset($data['password'])) $this->password = stripslashes( strip_tags( $data['password']));
    }

    public function storeFormValues( $params) {
        $this->__construct( $params );
    }

    public function Login() {
        $success = false;
        try{
            $pdo_connect = new PDO('mysql:host=localhost;dbname=camagru', "root", "root");
            $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM users WHERE login = :login AND password = :password LIMIT 1";
            $user = username;

            $stmt = $pdo_connect->prepare( $sql );
            $stmt->bindValue( "username", $this->username, PDO::PARAM_STR);
            $stmt->bindValue( "password", $this->password, PDO::PARAM_STR);
            $stmt->execute();

            $valid = $stmt->fetchColumn();

            if ($valid) {
                $sccess = true;
                session_start();

                session_regenerate_id();
                $_SESSION['user'] = $user['user'];
                session_write_close();
                echo('loggued_in');
                exit();
            }

            $pdo_connect = null;
            return $success;
        }catch (PDOException $e) {
            echo $e->getMessage();
            return $success;
        }
    }
}