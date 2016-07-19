<?php
/**
 * Created by PhpStorm.
 * User: opichou
 * Date: 7/18/16
 * Time: 7:25 PM
 */
include_once('config/pdo_connect.php');
if (isset($_POST['photo_id']) && isset($_POST['user_id']) && $_POST['photo_id'] != "" && $_POST['user_id'] != "") {
    $photo_id = $_POST['photo_id'];
    $user_id = $_POST['user_id'];
    $dataset = $camagru->getComments($user_id, $photo_id);
    foreach ($dataset as $i => $comment) {
        echo '<div id="comment-'.$i.'" class="comment">'.$comment['user_login'].': '.$comment['content'].'</div>';
    }
    ?>
    <div id="comment-form" class="comment">
        <form>
            <textarea name='comment_context' id='comment-content' maxlength='140'>Your comment here...</textarea>
            <input type='hidden' name='user_id' value='<?php echo $user_id; ?>' id='comment_user_id' />
            <input type='hidden' name='photo_id' value='<?php echo $photo_id; ?>' id="comment_photo_id"/>
            <input type='submit' name='submit' value='submit' id='comment_submit' />
        </form>
    </div>
    <?php
}
?>