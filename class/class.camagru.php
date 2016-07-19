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
        $this->user = $login;
        $image_info = getimagesize($image);
        if (($image_info != false)){
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
            if (imagecopy($camatmp, $overlayer, 0, 0, 0, 0, 1024, 768) != FALSE) {
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
            $stmt0 = $this->db->prepare("SELECT * FROM likes WHERE user_id =:user_id AND photo_id =:photo_id");
            $stmt0->bindParam(":user_id", $user_id);
            $stmt0->bindParam(":photo_id", $photo_id);
            $stmt0->execute();
            if ($stmt0->rowCount() == 0) {
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
            } else { return 0; }
        } catch (PDOException $e) {
            echo 'Connection failed: '. $e->getMessage();
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
        } catch (PDOException $e) {
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
                $stmt = $this->db->prepare("SELECT photo_id AS id, photo_url AS url FROM photos WHERE user_id=:user_id AND voided=0 ORDER BY photo_id ASC");
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
                $stmt = $this->db->prepare("SELECT photos.photo_id AS `id`, photos.photo_url AS `url`, photos.user_id AS `user_id`, l.likes AS `likes` , c.comments AS `comments`
                  FROM photos 
                  LEFT JOIN 
                    (SELECT photo_id, COUNT(like_date) AS likes FROM likes GROUP BY photo_id)
                    AS l ON photos.photo_id = l.photo_id
                  LEFT JOIN 
                    (SELECT photo_id, COUNT(comment_date) AS comments FROM comments GROUP BY photo_id)
                    AS c ON photos.photo_id = c.photo_id 
                  WHERE photos.voided = '0' AND photos.published = '1' 
                  ORDER BY id DESC LIMIT :offset, 10");
                $stmt->bindParam(':offset', intval(($page - 1) * 10), PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return 0;
            }
        } else { return 0; }
    }

    public function getComments($user_id, $photo_id) {
        if (isset($this->user_id) && ($user_id === $this->user_id)){
            try {
                $stmt = $this->db->prepare("SELECT comments.comment_text AS `content`, users.login AS `user_login` 
                  FROM comments LEFT JOIN users ON comments.user_id = users.user_id
                  WHERE photo_id =:photo_id AND comments.moderated = 0");
                $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return null;
            }

        }
    }

    public function postComment($user_id, $photo_id, $content) {
        if (isset($this->user_id) && $user_id === $this->user_id){
            try {
                $stmt = $this->db->prepare("INSERT INTO comments (photo_id, user_id, comment_text) VALUES (:photo_id, :user_id, :content)");
                $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
                $stmt->bindParam(":user_id",  $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":content", $content, PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount() != 0){
                    try {
                        $stmt2 = $this->db->prepare("SELECT users.email AS email, photos.photo_id, users.user_id FROM photos LEFT JOIN users on photos.user_id = users.user_id WHERE photo_id =:photo_id");
                        $stmt2->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
                        $stmt2->execute;
                        if ($stmt2->rowCount() === 1) {
                            $row = $stmt2->fetch();
                            $email = $row['email'];
                            $email_headers = "From: contact@liveoption.io\r\n";
                            $email_headers .= "MIME-Version: 1.0\r\n";
                            $email_headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
                            $email_content = "<html><head><style>body { \nbackground-color: darkgray; color: white; \nfont-family: 'Helvetica', 'Arial', sans-serif; }</style></head><body>\n";
                            $email_content .= "<h2>Hello,</h2>\n";
                            $email_content .= "Good news, someone has comented on your photo !\n";
                            $email_content .= "Here is the comment:\n";
                            $email_content .= "$content<br /><br />\n";
                            $email_content .= "See you soon on CAMAGRU !!!\n";
                            $email_content .= "<br />https://".$_SERVER['SERVER_NAME']."\n";
                            $email_content .= "</body></html>";

                            if (mail($email, "You received a new comment!", $email_content, $email_headers)) {
                                return 1;
                            } else {
                                return 0;
                            }
                        }
                        else {
                            return $stmt->rowCount();
                        }
                    } catch (PDOException $e) {
                        echo "Connection failed: ".$e->getMessage();
                        return 0;
                    }
                } else {
                    return 0;
                }
            } catch (PDOException $e) {
                echo "Connection failed: ".$e->getMessage();
                return 0;
            }
        }
    }

}
