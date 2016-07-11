/**
 * Created by Olivier on 7/5/2016.
 */
var video = document.getElementById("videoElement");
var canvas = document.getElementById('canvas');
var user_file = document.getElementById('file');
var preview = document.getElementById('preview');
var width = 1280;
var height = 720;

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

if (navigator.getUserMedia) {
    navigator.getUserMedia({video: true, audio: false}, handleVideo, videoError);
}

startbutton = document.getElementById('startbutton');

snap.addEventListener('click', function(ev){
    takepicture();
    ev.preventDefault();
}, false);

function handleVideo(stream) {
    video.src = window.URL.createObjectURL(stream);
}

function videoError(e) {
    // do something
}

function previewImage(event) {
    var reader = new FileReader();
    reader.readAsBinaryString(event.target.files[0]);
    var context = canvas.getContext('2d');
    context.drawImage(event.target.files[0], 0, 0);
}

function clearphoto() {
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    var data = canvas.toDataURL('image/png');
    photo.setAttribute('src', data);
}

function takepicture() {
    var context = canvas.getContext('2d');
    canvas.width = 1280;
    canvas.height = 720;
    context.drawImage(video, 0, 0, width, height);
    var data = canvas.toDataURL('image/png');

}
