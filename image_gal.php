<?php
/**
 * Created by PhpStorm.
 * User: opichou
 * Date: 7/14/16
 * Time: 3:30 PM
 */?>
<div class="centered" id="gallery">
<input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
<div id="cama_model" class="cama_preview">
<img width="100%">
<div class="cama cama_delete" onclick="delete_cama(this)">X&nbsp;&nbsp;</div>
<div class="cama cama_like" onclick="like_cama(this)">&nbsp;&nbsp;O</div>
<div class="cama cama_like_count" ></div>
</div>
</div>
