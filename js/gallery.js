/**
 * Created by opichou on 7/14/16.
 */

var likes_counter = [0];
var comments_counter = [0];
var comment_box_oppened = null;
var scroll_dest;






window.onload = function() {
    var cama_model = document.getElementById('cama_model');
    var gallery = document.getElementById('gallery');
    var cama_count = 0;
    var current_page = 1;
    var user_id = document.getElementById("user_id").getAttribute('value');
    var scroll_limit = 99999999;

    var var_y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

    function closeOpenedCommentBox() {
        if (comment_box_oppened != null) {
            document.getElementById(comment_box_oppened).parentNode.classList.add('restrict');
            document.getElementById(comment_box_oppened).remove();
            comment_box_oppened = null;
        }
    };

    function getPos(el) {
        for (var lx = 0, ly = 0;
             el != null;
             lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
        return {x: lx, y: ly};
    }

    function getScrollTop() {
        if (typeof pageYOffset != 'undefined') {
            return pageYOffset;
        }
        else {
            var B = document.body;
            var D = document.documentElement;
            D = (D.clientHeight) ? D : B;
            return D.scrollTop;
        }
    }

    function comment(id, user_id) {
        closeOpenedCommentBox();
        var tmp_commentbox = document.createElement('div');
        var tmp_cama = document.getElementById(id);
        tmp_commentbox.classList.add('comment-box');
        tmp_commentbox.setAttribute('id', "comment-" + id);
        tmp_cama.classList.remove('restrict');
        tmp_cama.appendChild(tmp_commentbox);
        scroll_dest = getPos(tmp_cama);
        var ajax = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('user_id', user_id);
        form_data.append('photo_id', id);
        ajax.open("POST", 'fetch_comments.php', true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                tmp_commentbox.innerHTML = ajax.responseText;
                window.scrollTo(0, scroll_dest.y);
                document.getElementById('comment-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    var content = document.getElementById('comment-content').value;
                    var ajax2 = new XMLHttpRequest();
                    var form_data2 = new FormData();
                    form_data2.append('user_id', user_id);
                    form_data2.append('photo_id', id);
                    form_data2.append('comment_content', content);
                    ajax2.open("POST", 'post_comment.php', true);
                    ajax2.onreadystatechange = function () {
                        if (ajax2.readyState == 4 && ajax2.status == 200) {
                            if (ajax2.responseText == 1) {
                                comments_counter[id]++;
                                var tmp_comments = document.getElementById(id).getElementsByClassName('cama_comment_count')[0];
                                if (comments_counter[id] < 2) {
                                    tmp_comments.innerHTML = comments_counter[id] + " comment";
                                } else {
                                    tmp_comments.innerHTML = comments_counter[id] + " comments";
                                }
                                ;
                                comment(id, user_id);
                            }
                        }
                    };
                    ajax2.send(form_data2);
                });
            }

        };
        ajax.send(form_data);
        comment_box_oppened = tmp_commentbox.getAttribute('id');
    }

    function add_cama(cama) {
        if (cama && cama.url && cama.id && cama.user_id && (cama != "error")) {
            var camagru = document.createElement("div");
            camagru.setAttribute('id', cama.id);
            if (gallery) {
                camagru.classList.add("cama_display");
                camagru.classList.add("restrict");
                camagru.innerHTML = cama_model.innerHTML;
                var tmp_img = camagru.getElementsByTagName('img');
                var tmp_input = camagru.getElementsByTagName('input');
                tmp_img[0].src = cama.url;
                tmp_input.name = "photo_id_" + cama_count;
                tmp_input.value = cama.id;
                gallery.appendChild(camagru);
                camagru = document.getElementById(cama.id);
                if (user_id != cama.user_id) {
                    var option_tab = document.createElement('div');
                    option_tab.classList.add('absolute-0');
                    option_tab.classList.add('cama_display');
                    option_tab.innerHTML = '<div class="cama cama_like layer-2">&nbsp;</div>';
                    var tmp_like = option_tab.getElementsByClassName('cama_like')[0];
                    tmp_like.addEventListener('click', function () {
                        var user_id = document.getElementById("user_id").getAttribute('value');
                        var photo_id = this.parentNode.parentNode.getAttribute('id');
                        var ajax = new XMLHttpRequest();
                        var form_data = new FormData();
                        form_data.append('photo_id', photo_id);
                        form_data.append('user_id', user_id);
                        ajax.open('POST', 'like_camagru.php', true);
                        ajax.onreadystatechange = function () {
                            if (ajax.readyState == 4 && ajax.status == 200) {
                                if (ajax.responseText == "1") {
                                    likes_counter[photo_id]++;
                                    var tmp_likes = document.getElementById(photo_id).getElementsByClassName('cama_like_count')[0];
                                    if (likes_counter[cama.id] < 2) {
                                        tmp_likes.innerHTML = likes_counter[cama.id] + " like";
                                    } else {
                                        tmp_likes.innerHTML = likes_counter[cama.id] + " likes";
                                    }
                                }
                            }
                        }
                        ajax.send(form_data);
                    });
                } else {
                    var option_tab = document.createElement('div');
                    option_tab.classList.add('absolute-0');
                    option_tab.classList.add('cama_display');
                    option_tab.innerHTML = '<div class="cama cama_delete_gal layer-2">X&nbsp;&nbsp;</div>';
                    var tmp_delete = option_tab.getElementsByClassName('cama_delete_gal')[0];
                    tmp_delete.addEventListener('click', function () {
                        var tmp_id = this.parentNode.parentNode.getAttribute('id');
                        var tmp_div = this.parentNode.parentNode;
                        var ajax = new XMLHttpRequest();
                        ajax.open("GET", 'delete_camagru.php?id=' + tmp_id, true);
                        ajax.onreadystatechange = function () {
                            if (ajax.readyState == 4 && ajax.status == 200) {
                                if (ajax.responseText == "1") {
                                    tmp_div.remove();
                                }
                            }
                        };
                        ajax.send();
                    });
                }
                camagru.appendChild(option_tab);
                var tmp_likes = document.createElement('div');
                var tmp_comments = document.createElement('div');
                tmp_likes.classList.add('cama_like_count');
                tmp_likes.classList.add('layer-2');
                tmp_comments.classList.add('cama_comment_count');
                tmp_comments.classList.add('layer-2');
                if (isNaN(parseInt(cama.likes)) || cama.likes == 0) {
                    likes_counter[cama.id] = '0';
                }
                else {
                    likes_counter[cama.id] = cama.likes;
                }
                if (likes_counter[cama.id] < 2) {
                    tmp_likes.innerHTML = likes_counter[cama.id] + " like";
                } else {
                    tmp_likes.innerHTML = likes_counter[cama.id] + " likes";
                }
                if (isNaN(parseInt(cama.comments)) || cama.comments == 0) {
                    comments_counter[cama.id] = '0';
                }
                else {
                    comments_counter[cama.id] = cama.comments;
                }
                if (comments_counter[cama.id] < 2) {
                    tmp_comments.innerHTML = comments_counter[cama.id] + " comment";
                } else {
                    tmp_comments.innerHTML = comments_counter[cama.id] + " comments";
                }
                if (cama.user_id != user_id) {
                    tmp_likes.addEventListener('click', function () {
                        var user_id = document.getElementById("user_id").getAttribute('value');
                        var photo_id = this.parentNode.parentNode.getAttribute('id');
                        var ajax = new XMLHttpRequest();
                        var form_data = new FormData();
                        form_data.append('photo_id', photo_id);
                        form_data.append('user_id', user_id);
                        ajax.open('POST', 'like_camagru.php', true);
                        ajax.onreadystatechange = function () {
                            if (ajax.readyState == 4 && ajax.status == 200) {
                                if (ajax.responseText == "1") {
                                    likes_counter[photo_id]++;
                                    var tmp_likes = document.getElementById(photo_id).getElementsByClassName('cama_like_count')[0];
                                    if (likes_counter[cama.id] < 2) {
                                        tmp_likes.innerHTML = likes_counter[cama.id] + " like";
                                    } else {
                                        tmp_likes.innerHTML = likes_counter[cama.id] + " likes";
                                    }
                                }
                            }
                        }
                        ajax.send(form_data);
                    });
                }
                tmp_comments.addEventListener('click', function () {
                    var user_id = document.getElementById("user_id").getAttribute('value');
                    var photo_id = this.parentNode.parentNode.getAttribute('id');
                    if (("comment-"+photo_id) == comment_box_oppened) {
                        closeOpenedCommentBox();
                    } else {
                        comment(photo_id, user_id);
                    }
                });
                camagru.appendChild(option_tab);
                option_tab.appendChild(tmp_likes);
                option_tab.appendChild(tmp_comments);
                cama_count++;
                return 1;
            } else {
                console.log("error appending child");
                return 0;
            }

        }
    }

    function pageload(page) {
        var ajax = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('page', current_page);
        ajax.open('POST', 'load_photos.php', true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                if (ajax.responseText != "" && ajax.responseText != "error") {
                    var dataset = JSON.parse(ajax.responseText);
                    if (dataset == "error") {
                        console.log("error on loading page content");
                        return 0;
                    }
                    current_page++;
                    dataset.forEach(function (current) {
                        add_cama(current);

                    });
                    scroll_limit = (document.getElementsByTagName('img')[cama_count].clientHeight) * (cama_count / 2) - var_y;
                }
            }
        };
        ajax.send(form_data);
        return 1;
    }

    window.addEventListener('scroll', function () {
        if ((cama_count > 0) && ((cama_count) % 10 == 0) && getScrollTop() > scroll_limit) {
            pageload(current_page);
        }
    });

    pageload(current_page);
};
