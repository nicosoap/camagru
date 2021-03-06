<div class="main">
    <form id="fileuploadform" method="post" enctype="multipart/form-data">
        <video autoplay id="videoElement" class="preview layer-1 camagru-visual"></video>
        <canvas id="canvas" class="hidden preview camagru-visual" ></canvas>
        <div id="videoFallback" class="hidden preview layer-1 camagru-visual">
            <img id="preview" class="camagru-visual" />
            <input type="hidden" name="MAX_FILE_SIZE" value="9000000" />
            <input type="hidden" name="user" value="<?php echo $_SESSION['login']; ?>">
            <input type="file" accept="image/*" name="userfile" id="file" class="inputfile inputfile-1" />
            <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
        </div>
        <div class="cama_mask screen camagru-visual"><img id="overlayer" class="camagru-visual"/>
            <div class="control_panel screen camagru-visual">
                <div class="control_button change less" id="cama_less"><</div>
                <div class="control_button button_snap" id="cama_snap">SNAP</div>
                <div class="control_button change more" id="cama_more">></div>
            </div>
        </div>
    </form>
</div>
