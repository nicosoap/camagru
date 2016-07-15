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
    private $cama_id = null;
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
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', true);
        $this->user = $this->db->quote($login);
        $image_info = getimagesize($image);
        if (($image_info != false) && (($image_info[0]/$image_info[1]) > 1.25) && (($image_info[0]/$image_info[1]) < 1.48) && ($image_info[0] <= 5120) && ($image_info[0] >= 155)){
            switch ($image_info['mime']) {
                case 'image/gif': $camatmp = imagecreatefromgif($image); break;
                case 'image/jpeg': $camatmp = imagecreatefromjpeg($image); break;
                case 'image/png': $camatmp = imagecreatefrompng($image); break;
                default: echo "file error" ; exit; break;
            };
            $camatmp =
            $camatmp = imageaffine($camatmp, [1024/$image_info[0], 0, 0, 768/$image_info[1], 0, 0]);
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
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return 0;
        }
    }

    public function getLikes($photo_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM likes WHERE photo_id =:photo_id");
            $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Connection failed :" . $e->getMessages();
        }
    }

    public function getDetailedLikes($photo_id) {
        try {
            $stmt = $this->db->prepare("SELECT users.login FROM likes LEFT JOIN users ON likes.user_id = users.user_id WHERE likes.photo_id =:photo_id");
            $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Connection failed : $e->getMessage()";
        }
    }

    public function getURL() {
            return ($this->cama);
    }

    public function getID() {
        if (isset($this->cama_id) && $this->cama_id != "")
            return $this->cama_id;
        else if (isset($this->cama) && $this->cama != "") {
            try {
                $stmt = $this->db->prepare("SELECT photo_id FROM photos WHERE photo_url = :photo_url LIMIT 1");
                $stmt->execute(array(":photo_url" => $this->cama));
                $photo = $stmt->fetch();
                $this->cama_id = $photo['photo_id'];
                return $this->cama_id;
            } catch (PDOException $e) {
                echo "Connection failed:".$e->getMessage();
            }
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
                $stmt = $this->db->prepare("SELECT photo_id AS id, photo_url AS url FROM photos WHERE user_id=:user_id AND voided=0 ORDER BY photo_id DESC");
                $stmt->execute(array(':user_id' => $user_id));
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return 0;
            }
        }
    }

    public function getAllPhotos($user_id, $page) {
        if (isset($user_id) && ($user_id != "") && ($page >= 0)) {
            try {
                $stmt = $this->db->prepare("( SELECT photos.photo_id AS `id`, photos.photo_url AS `url`, photos.user_id AS `user_id`, COUNT(likes.like_date) AS `likes` 
                FROM photos LEFT JOIN likes ON photos.photo_id = likes.photo_id WHERE photos.voided = '0' AND photos.published = '1' GROUP BY `id` ) 
                UNION ALL ( SELECT photos.photo_id AS `id`, photos.photo_url AS `url`, photos.user_id AS `user_id`, COUNT(likes.like_date) AS `likes` 
                FROM photos RIGHT JOIN likes ON photos.photo_id = likes.photo_id 
                WHERE photos.voided = '0' AND photos.published = '1' GROUP BY `id` ) ORDER BY id DESC LIMIT :offset, 25;");
                $stmt->bindParam(':offset', intval(($page - 1) * 25), PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return 0;
            }
        } else { return 0; }
    }

    public function quote($str) {
        return $this->db->quote($str);
    }
}
