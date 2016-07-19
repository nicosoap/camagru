<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/2/2016
 * Time: 2:25 PM
 */
require_once ("database.php");
try {
    $pdo_connect = new PDO('mysql:host=localhost', $DB_USER, $DB_PASSWORD);
    $pdo_connect ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_connect->query('CREATE DATABASE IF NOT EXISTS camagru DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;');
    $pdo_connect->query('USE camagru;');
    $pdo_connect->query("CREATE TABLE IF NOT EXISTS users (
                  user_id int(11) NOT NULL AUTO_INCREMENT,
                  login varchar(100) NOT NULL,
                  password varchar(999) NOT NULL,
                  email varchar(120) NOT NULL,
                  FB_access_token varchar(999),
                  is_admin BOOLEAN NOT NULL DEFAULT FALSE,
                  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  verified BOOLEAN DEFAULT FALSE,
                  PRIMARY KEY (user_id),
                  UNIQUE (login)
                  ) COMMENT='User table';");
    $pdo_connect->query("INSERT INTO users ( login, password, email, is_admin, verified )
                  VALUES ('admin', '".hash("whirlpool", "admin")."', 'opichou@student.42.fr', TRUE, TRUE);");
    $pdo_connect->query("CREATE TABLE IF NOT EXISTS photos (
                  photo_id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  published BOOLEAN NOT NULL DEFAULT TRUE,
                  voided BOOLEAN NOT NULL DEFAULT FALSE,
                  photo_url varchar(500) NOT NULL,
                  PRIMARY KEY (photo_id)
                  ) COMMENT='Photo table';");
    $pdo_connect->query("CREATE TABLE IF NOT EXISTS likes (
                  like_id int(22) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  photo_id int(11) NOT NULL,
                  like_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (like_id)
                  ) COMMENT='Likes table';");
    $pdo_connect->query("CREATE TABLE IF NOT EXISTS comments (
                  comment_id int(22) NOT NULL AUTO_INCREMENT,
                  comment_text TEXT NOT NULL,
                  user_id int(11) NOT NULL,
                  photo_id int(11) NOT NULL,
                  moderated int(11) NOT NULL DEFAULT '0',
                  comment_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (comment_id)
                  ) COMMENT='Comments table';");
    $pdo_connect->query("CREATE TABLE IF NOT EXISTS tokens (
                  token_id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  token TEXT NOT NULL,
                  usag TEXT NOT NULL,
                  status BOOLEAN DEFAULT TRUE,
                  PRIMARY KEY (token_id)
                  ) COMMENT='Likes table';");
    $pdo_connect = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
header("location: ../index.php");
?>
