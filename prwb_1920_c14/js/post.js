$(function() {
    $("#menu-php").hide();
    $("#menu-js").css({
        "display" : "flex"
    });
    $("#post-php").hide();
    $("#btnsearch").hide();
    getPosts();
    btnAction();
});

var listPosts;
var datas;
var pagina;
var current_page = 1;
var booksPage = 3;

function getPosts() {
    listPosts = $("#posts-js");
    pagina = $("#posts-pagination");
    $.get("post/newestJson", function(data) {
        datas = jQuery.parseJSON(data);
        posts(datas);
    });

    $("#search").keyup(function() {
        console.log($("#search").val())
        $.post("post/searchJson", {terme: $("#search").val()}, function (data){
            datas = jQuery.parseJSON(data);
            posts(datas);
        })
    });
}

function btnAction() {
    $("#newest").click(function() {
        $.get("post/newestJson", function(data) {
            datas = jQuery.parseJSON(data);
            posts(datas);
        });
    });

    $("#unanswered").click(function() {
        $.get("post/unansweredJson", function(data) {
            datas = jQuery.parseJSON(data);
            posts(datas);
        });
    });

    $("#active").click(function() {
        $.get("post/activeJson", function(data) {
            datas = jQuery.parseJSON(data);
            posts(datas);
        });
    });

    $("#vote").click(function() {
        $.get("post/voteJson", function(data) {
            datas = jQuery.parseJSON(data);
            posts(datas);
        });
    });
}

function next(next, url) {
    console.log(url)
    $.get(url + "/" + next, function(data) {
        datas = jQuery.parseJSON(data);
        posts(datas);
    })
}

function prev(prev, url) {
    $.get(url + "/" + prev, function(data) {
        datas = jQuery.parseJSON(data);
        posts(datas);
    })
}

function pagee(page, url) {
    $.get(url + "/" + page, function (data){ 
        datas = jQuery.parseJSON(data); 
        console.log(datas)
        posts(datas);
    })
}

function posts(datas) {
    listPosts.html("");
    pagina.html("");

    var table = "";
    table += "<div style = 'margin-top: 15px;' id = 'post-php'>";
    for(var index = 0; index < datas.posts.length; index++) {
        if(datas.posts[index].Title !== "") {
            table += "<div class='card border-info mb-3' style='max-width: 80rem;'>";
            table += "<div class='card-header'><div><a href= post/getPost/" + datas.posts[index].PostId + ">" + datas.posts[index].Title + "</a></div>";
            table += "<div>"; 
            for(var idx = 0; idx < datas.posts[index].tags.length; idx++) {
                table += "<a href='post/getPostByTag/"+ datas.posts[index].tags[idx].TagId +"' style='background-color:#D0D0D0; margin-left:7px'>" + datas.posts[index].tags[idx].TagName +"</a>";
            }
            table += "</div>";
            table += "</div>";
            table += "<div class='card-body'>";
            table += "<h4 class='card-title'></h4>"
            table += " <p class='card-text'>" + datas.posts[index].getUser + "(" + datas.posts[index].answers + " réponse(s)) " + datas.posts[index].Timestamp + "</p>";
            table += "</div>"; 
            table += "</div>";         
        }
    }
    
    var pagination = "<ul class='pagination pagination-lg'>";
        if(datas.prev >= 1) {
            pagination += "<li class='page-item' onclick = 'prev("+ datas.prev + ",`" + datas.url + "`)'><a class='page-link' >&laquo;</a></li>"
        }
        for(var p = 1; p <= datas.nbPages; ++p) {
            pagination += "<li"; 
            if(p === datas.page) {
                pagination += "class='page-item active'";
            } else {
                pagination += "class='page-item'";
            }
            pagination += "><a class='page-link' onclick = 'pagee(" + p + ",`" + datas.url + "`)' >" + p + "</a></li>";
        }
        if(datas.next <= datas.nbPages) {
            pagination += "<li class='page-item' onclick = 'next(" + datas.next + ",`" + datas.url + "`)' ><a  class='page-link' >&raquo;</a></li>";
        }

        pagination += "</ul>";
        listPosts.append(table);
        pagina.append(pagination);
}
