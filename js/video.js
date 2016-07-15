/**
 * Created by Olivier on 7/5/2016.
 */

function delete_cama(elem) {
    var tmp_id = elem.parentNode.getAttribute('id');
    var ajax = new XMLHttpRequest();
    ajax.open("GET", 'delete_camagru.php?id='+tmp_id, true);
    ajax.onreadystatechange = function() {
        console.log(ajax.responseText);
        if (ajax.responseText == "1") {
            elem.parentNode.remove();
        }
    }
    ajax.send();
}


window.onload = function(){
    var sidebar = document.getElementById('sidebar');
    var video = document.getElementById("videoElement");
    var canvas = document.getElementById('canvas');
    var user_file = document.getElementById('file');
    var preview = document.getElementById('preview');
    var videoFallback = document.getElementById('videoFallback');
    var fileuploadform = document.getElementById('fileuploadform');
    var isvalidfile = false;
    var sidebar = document.getElementById('sidebar');
    var cama_model = document.getElementById('cama_model');
    var cama_count = 0;
    var file;
    var isnowebcam;
    startbutton = document.getElementById('startbutton');
    var width = 1024;
    var height = 768;
    var overlayer_div = document.getElementById('overlayer').getElementsByTagName('img')[0];
    var more = document.getElementById('cama_more');
    var less = document.getElementById('cama_less');
    var snap = document.getElementById('cama_snap');
    var allowedTypes = ['jpg', 'jpeg', 'gif', 'png'];
    var overlayer = ['img/overlay/01.png', 'img/overlay/02.png', 'img/overlay/03.png', 'img/overlay/04.png', 'img/overlay/05.png', 'img/overlay/06.png', ];
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

    if (navigator.getUserMedia) {
        navigator.getUserMedia({ audio: false, video: { width: width, height: height } }, handleVideo, videoError);
    }

    more.addEventListener('click', changeOverlayer('more', overlayer_div.src));
    less.addEventListener('click', changeOverlayer('less', overlayer_div.src));
    snap.addEventListener('click', fileuploadform.submit());

    function changeOverlayer(way, actual) {
        if (way == 'more') {
            overlayer_div.src = overlayer[(overlayer.indexOf(actual) + 1) % 6];
        }else {
            overlayer_div.src = overlayer[(overlayer.indexOf(actual) + 5) % 6];
        }
    }

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
                    preview.classList.remove('hidden');
                    preview.src = reader.result;
                }, false);
                reader.readAsDataURL(file);
            }
        }, false);
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
        if (cama && cama.url && cama.id && (cama != "error")) {
            var camagru = document.createElement("div");
            camagru.setAttribute('id', cama.id);
            if (sidebar) {
                camagru.classList.add("cama_preview");
                camagru.innerHTML = cama_model.innerHTML;
                var tmp_img = camagru.getElementsByTagName('img');
                var tmp_input = camagru.getElementsByTagName('input');
                tmp_img[0].src = cama.url;
                tmp_input.name = "photo_id_"+cama_count;
                tmp_input.value = cama.id;
                sidebar.appendChild(camagru);
                cama_count += 1;
                return 1;
            } else {
                console.log("error appending child");
                return 0;
            }

        }
    };

    fileuploadform.addEventListener('submit', function(e) {
        e.preventDefault();
        if (isnowebcam == true && isvalidfile == true) {
            var ajax = new XMLHttpRequest();
            var form_data = new FormData();
            form_data.append('userfile', file, file.name);
            form_data.append('overlayer', overlayer[overlay]);
            ajax.open("POST",'make_camagru.php',true);
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    add_cama(JSON.parse(ajax.responseText));
                    return 1;
                }
            }
            ajax.send(form_data);
            return 1;
        }
        else {
            console.log("takepicture from webcam");
            canvas.classList.remove('hidden');
            var context = canvas.getContext('2d');
            canvas.width = width;
            canvas.height = height;
            context.drawImage(video, 0, 0, 1280, 720);
            var tmp_img = new Image();
            var d = new Date();
            var data = canvas.toDataURL('image/png');
            tmp_img.src = data;
            tmp_img.name = d.getDate();
            canvas.classList.add('hidden');
            var ajax = new XMLHttpRequest();
            var form_data = new FormData();
            form_data.append('userfile', data);
            form_data.append('webcam', "1");
            form_data.append('overlayer', overlayer[overlay]);
            ajax.open("POST",'make_camagru.php',true);
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    add_cama(JSON.parse(ajax.responseText));
                    return 1;
                }
            };
            ajax.send(form_data);
            return 1;
        }

    }, false);

    function pageload(){
        var ajax = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('perso', '1');
        ajax.open('POST', 'load_photos.php', true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                if (ajax.responseText != "" && ajax.responseText != "error") {
                    var dataset = JSON.parse(ajax.responseText);
                    dataset.forEach(function (current) {
                        add_cama(current);
                    });
                }
            }
        };
        ajax.send(form_data);
        return 1;
    }
    pageload();
};
