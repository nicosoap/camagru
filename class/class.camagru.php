<?php

/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 7/4/2016
 * Time: 2:52 AM
 */
class camagru
{
    private $cama = null;
    private $user = null;
    private $user_id = null;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        if (isset($_SESSION['login']) && $_SESSION['login'] != "") {
            $this->user = $_SESSION['login'];
        }
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
            $this->user_id = $_SESSION['user_id'];
        }
    }

    public function makeCama($image, $overlay, $login) {
        //ini_set('error_reporting', E_ALL);
        //ini_set('display_errors', true);
        $this->user = $login;
        $image_info = getimagesize($image);
        if (($image_info != false) && (($image_info[0]/$image_info[1]) > 1.7) && (($image_info[0]/$image_info[1]) < 1.85) && ($image_info[0] <= 5120) && ($image_info[0] >= 155)){
            switch ($image_info['mime']) {
                case 'image/gif': $camatmp = imagecreatefromgif($image); break;
                case 'image/jpeg': $camatmp = imagecreatefromjpeg($image); break;
                case 'image/png': $camatmp = imagecreatefrompng($image); break;
                default: echo "file error" ; exit; break;
            };
            $camatmp =
            $camatmp = imageaffine($camatmp, [1280/$image_info[0], 0, 0, 720/$image_info[1], 0, 0]);
            if ($camatmp != FALSE) {
            }
            $overlayer = imagecreatefrompng($overlay);
            imagealphablending($overlayer, true);
            imagesavealpha($overlayer, true);
            imagesavealpha($camatmp, true);
            imagealphablending($camatmp, true);
            if (imagecopy($camatmp, $overlayer, 0, 0, 0, 0, 1280, 720) != FALSE) {
                $filedir = "uploads/";
                $this->cama = $filedir.$this->user.time().".png";
                if (imagepng($camatmp, $this->cama) != FALSE) {
                    try {
                        $stmt = $this->db->prepare("INSERT INTO photos(user_id, photo_url) SELECT user_id, :cama FROM users WHERE login=:login");
                        $stmt->execute(array(':cama' => $this->cama, ':login' => $this->user));
                        return 1;
                    } catch (PDOException $e) {
                        echo 'Connection failed: ' . $e->getMessage();
                    }
                }


            }
        }
        return 0;
    }

    public function likePhoto($user_id, $photo_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, photo_id) VALUES (:user_id, :photo_id)");
            $stmt->execute(array(":user_id" => $user_id, ":photo_id" => $photo_id));
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getLikes($photo_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM likes WHERE photo_id =:photo_id");
            $stmt->execute(array(":photo_id" => $photo_id));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Connection failed :" . $e->getMessages();
        }
    }

    public function getDetailedLikes($photo_id) {
        try {
            $stmt = $this->db->prepare("SELECT users.login FROM likes LEFT JOIN users ON likes.user_id = users.user_id WHERE likes.photo_id =:photo_id");
            $stmt->execute(array(":photo_id" => $photo_id));
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Connection failed : $e->getMessage()";
        }
    }

    public function getURL($photo_id) {
        if (isset($photo_id) && $photo_id != ""){
            try {
                $stmt = $this->db->prepare("SELECT photo_url FROM photos WHERE photo_id = :photo_id");
                $stmt->execute(array(':photo_id' => $photo_id));
                $photo = $stmt->fetch(PDO::FETCH_ASSOC);
                return $photo['photo_url'];
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return (0);
            }
        } elseif (isset($this->cama) && $this->cama != "") {
            return ($this->cama);
        } else {
            return 0;
        }
    }

    public function publishPhoto($photo_id, $user_id) {
        try {
            $stmt = $this->db->prepare("UPDATE photos SET published=1 WHERE photo_id=:photo_id AND user_id=:user_id");
            $stmt->execute(array(':photo_id' => $photo_id, ':user_id' => $user_id));
            if ($stmt->rowCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOEsception $e) {
            echo "Connection failed: ".$e->getMessage();
        }
    }

    public function deletePhoto($photo_id, $user_id) {
        try {
            $stmt = $this->db->prepare("UPDATE photos SET voided=1 WHERE photo_id=:photo_id AND user_id=:user_id");
            $stmt->execute(array(':photo_id' => $photo_id, ':user_id' => $user_id));
            if ($stmt->rowCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOEsception $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getMyPhotos($user_id) {
        if (isset($user_id) && $user_id != "") {
            try {
                $stmt = $this->db->prepare("SELECT photo_id, photo_url FROM photos WHERE user_id=:user_id AND voided=0 ORDER BY photo_id DESC");
                $stmt->execute(array(':user_id' => $user_id));
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
            }
        }
    }

}
