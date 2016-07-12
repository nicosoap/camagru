/**
 * Created by Olivier on 7/5/2016.
 */
window.onload = function(){
    var video = document.getElementById("videoElement");
    var canvas = document.getElementById('canvas');
    var user_file = document.getElementById('file');
    var preview = document.getElementById('preview');
    var snap = document.getElementById('snap');
    var videoFallback = document.getElementById('videoFallback');
    var isvalidfile = false;
    var sidebar = document.getElementById('sidebar');
    var cama_model = document.getElementById('cama_model');
    var cama_count = 0;
    var file;
    var isnowebcam;
    startbutton = document.getElementById('startbutton');
    var width = 1280;
    var height = 720;
    var allowedTypes = ['jpg', 'jpeg', 'gif', 'png'];
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

    if (navigator.getUserMedia) {
        navigator.getUserMedia({video: true, audio: false}, handleVideo, videoError);
    }

    snap.addEventListener('click', takepicture, false);


    function handleVideo(stream) {
        console.log("handleVideo");
        isvalidfile = true;
        isnowebcam = false;
        video.src = window.URL.createObjectURL(stream);
    }

    function videoError(e) {
        console.log("videoError");
        isnowebcam = true;
        videoFallback.classList.remove('hidden');
        video.classList.add('hidden');
        canvas.classList.add('hidden');
        user_file.addEventListener('change', function (e) {
            var imgType = (e.target.files[0].name.split('.')).pop().toLowerCase();
            if (allowedTypes.indexOf(imgType) != -1) {
                isvalidfile = true;
                file = e.target.files[0];
                var reader = new FileReader();
                reader.addEventListener('load', function (e) {
                    //var tmp;
                    //tmp.img = reader.result;
                    preview.classList.remove('hidden');
                    preview.src = reader.result;
                }, false);
                reader.readAsDataURL(file);
            }
        }, false);
    }

    function saveImage() {
        var canvasData = canvas.toDataURL("image/png");
        var ajax = new XMLHttpRequest();
        ajax.open("POST",'testSave.php',false);
        ajax.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                console.log(ajax.responseText);
            }
        };
        ajax.setRequestHeader('Content-Type', 'application/upload');
        ajax.send("imgData="+canvasData);

    }

    function delete_cama(elem) {
        var tmp_id = elem.parentNode.getAttribute('id');
        var ajax = new XMLHttpRequest();
        ajax.open("GET", 'delete_camagru.php?id='+tmp_id, true);
        ajax.onreadystatechange = function() {
            console.log(ajax.responseText);
            sidebar.removeChild(tmp_id);
        }
        ajax.send();
    }

    function publishphoto(elem){
        var tmp_id = elem.parentNode.getAttribute('id');
        var ajax = new XMLHttpRequest();
        ajax.open("GET", 'publish_camagru.php?id='+tmp_id, true);
        ajax.onreadystatechange = function() {
            console.log(ajax.responseText);
        }
        ajax.send();
    }

    function add_cama(cama) {
        if (cama.url != "error") {
            console.log(cama.url);
            console.log(cama.id);
            var camagru = document.createElement("div");
            camagru.setAttribute('id', cama.id);
            if (sidebar) {
                sidebar.appendChild(camagru);
                camagru.classList.add("cama_preview");
                camagru.innerHTML = cama_model.innerHTML;
                var tmp_img = camagru.getElementsByTagName('img');
                var tmp_input = camagru.getElementsByTagName('input');
                tmp_img.setAttribute('src', cama.url);
                tmp_input.setAttribute('name', "photo_id_"+cama_count);
                tmp_input.setAttribute('value', cama.id);
                cama_count += 1;
                console.log(cama_count);
            } else {
                console.log("error appending child");
            }

        }
    }

    function takepicture() {
        console.log("takepicture");
        if (isnowebcam == true && isvalidfile == true) {
            console.log("take picture from file upload");
            console.log(file);
            console.log(file.name);
            var ajax = new XMLHttpRequest();
            var fileuploadform = document.getElementById('fileuploadform');
            var form_data = new FormData();
            form_data.append('userfile', file, file.name);
            ajax.open("POST",'make_camagru.php',true);
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    console.log("returned value");
                    console.log(ajax.responseText);
                    add_cama(JSON.parse(ajax.responseText));
                }
            }
            ajax.send(form_data);
        }
        else {
            console.log("takepicture from webcam");
            canvas.classList.remove('hidden');
            var context = canvas.getContext('2d');
            canvas.width = 1280;
            canvas.height = 720;
            context.drawImage(video, 0, 0, width, height);
            var data = canvas.toDataURL('image/png');
            canvas.classList.add('hidden');
        }

    };
};