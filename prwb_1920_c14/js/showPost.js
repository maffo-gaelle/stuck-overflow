$(function() {
    $("#post-php").hide();
    getPost();
});

var post;
function getPost() {
    var postId = $(".PostId").val();
    post = $("#post-js");
    $.get("post/getPostJson/" + postId, function(data) {
        datas = jQuery.parseJSON(data);
        showPost(datas);
    });
}

function showComment(id) {
    $("." + id).toggle('slide', {percen: 50}, 500);
    $(".add-comment" + id).hide();
}

function annuler(id) {
    $("." + id).toggle('slide', {percen: 50}, 500);
    $(".add-comment" + id).show();
}

function addComment(id) {
    $.post("comment/addCommentJson", {PostId: id, Body: $(".Body" +id).val()}, function(data){
        datas = jQuery.parseJSON(data);
        showPost(datas);
    })
}

function showPost(datas) {
    post.html("");
    var table = '<h1 class="display-3">'+ datas.post.Title + '</h1>';
    table += '<p> Asked ' + datas.post.Timestamp +' by<span style = "color:blue"> ' + datas.post.postUser + '</span>';
    
    if(datas.user && datas.user.UserId === datas.post.AuthorId || datas.user && datas.user.Role === "admin") {
        table += '<a href="post/editPost/'+ datas.post.PostId + '" class="card-link"><i class="fas fa-pen" style = "font-size:12px; margin-left:5px"></i></a>';
        if(datas.user && datas.post.ParentId === null && datas.post.countAnswer === 0 && datas.post.countComment === 0 || datas.user && datas.post.ParentId !== null && datas.post.countComment === 0) {
            table += '<a href="post/delete/'+ datas.post.PostId + '"  class="card-link"><i class="fas fa-trash" style = "font-size:12px; margin-left : -18px;"></i></a>';
        };   
    }
    table += '</p>';
    table += '<p>';
    
    if(datas.post.ParentId === null) {
        table += "<span>";
        for(var i = 0; i < datas.post.tags.length; ++i) {
            table += '<span style= "background-color:#D0D0D0; margin-right:7px""><a href="post/getPostByTag/' + datas.post.tags[i].TagId + '">'+ datas.post.tags[i].TagName + '</a>  ';
            if(datas.user && datas.post.ParentId === null && datas.user.Role === "admin" || datas.user && datas.post.ParentId === null && datas.user.UserId === datas.post.AuthorId) {
                table += '<a href="post/deleteTagPost/'+ datas.post.tags[i].TagId + '/' + datas.post.PostId + '"><i class="fa fa-remove" style = "margin-right : 3px"></i></a>';
            }
            table += '</span>';
        }
        if(datas.user && datas.post.ParentId === null && datas.user.Role === "admin" || datas.user && datas.post.ParentId === null && datas.user.UserId === datas.post.AuthordId) {
            table += '<form action="post/addPostTag/' +datas.post.PostId +'" method="post" class="col-md-4" style = "display : inline; !important">';
            table += '<select name="tagId" class="custom-select" style ="width : 60px; margin-right : 10px;>';
            for(var t = 0; t < datas.post.allTags.length; ++t) {
                if($.inArray(datas.post.allTags[t], datas.post.allTags)) {
                    table += '<option value="'+datas.post.allTags[t].TagId+'">' +datas.post.allTags[t].TagName;+ '</option>';
                }
            }
            table += "</select>";
            table += '<button type="submit" value="" style = "display:inline"> + </button>'
            table += '</form>';
        }
        table += "</span>";
    }
    table += '</p>';
    table += '<div class="card border-info mb-3" style="max-width: 100%;">';
    table += '<div class="card-body">';
    table += '<div class = "row">';
    table += '<div class = "col-lg-1">';
    table += '<div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">';
    table += '<div class="card-body">';
    if(datas.user) {
        table += '<form action = "vote/voteUp/'+ datas.post.PostId + '" method = "post">';
        table += '<button type = "submit" style="border : none; background-color : white;" name = "voteUp"><i class="fa fa-caret-up" style="font-size:44px; color: green"></i></button>';
        table += '</form>';
    }
    table += '<h4 class="card-title">'+ datas.post.score + '</h4>';
    if(datas.user) {
    table += '<form action = "vote/voteDown/'+ datas.post.PostId + '" method = "post">';
    table += '<button style="border : none; background-color : white;"><i class="fa fa-caret-down" style="font-size:44px;  color: red;"></i></button>';
    table += '</form>';
    }
    table += '<p>';

    
    if(datas.post.acceptAnswer) {
        table += '<form action = "post/accept/' + datas.post.PostId + '" method = "post">'; 
        table += '<i class="fa fa-check" style="font-size:16px;color:green"></i>';  
        table += '<button style="border : none; background-color : white;"><i class="fa fa-times" style="font-size:16px;color:red"></i></button>';
        table += '</form>';
    }
    table += "</p>";
    table += '</div>';
    table += '</div>';
    table += '</div>';

    table += '<div class = "col-lg-8">';
    table += '<div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">';
    table += '<div class="card-body">';
    table += '<p class="card-text">' + datas.post.Body ;
    
    table += '</p>';
    table += '<div>';
    
    for(var index = 0; index < datas.post.comments.length; index++) {
        table += "<hr>";
        table += "<p><small>" + datas.post.comments[index].Body +"</small></p>";
        table += "<p><small>par "+ datas.post.comments[index].User +" " + datas.post.comments[index].Timestamp + "</small>";
        if(datas.user && datas.post.comments[index].UserId === datas.user.UserId || datas.user && datasdatas.user.Role === "admin") {
            table += "<small> <a href = 'comment/editComment/"+ datas.post.comments[index].CommentId+"' title = modifier><i class='fas fa-pen' style = 'font-size:8px;' ></i></a></small>" 
            table += "<small> <a href= 'comment/delete/"+ datas.post.comments[index].CommentId +"' title = supprimer><i class='fas fa-trash' style = 'font-size:8px;' ></i></a></small>"
        }
    }

    if(datas.user) {
        table += '<div class="'+ datas.post.PostId + '" style = "display: none;">';
        table += '<div style = "display: flex; margin-bottom: 10px">';
        table += '<textarea style = "width:350px; margin-right: 10px; padding: 5px" class = "Body'+ datas.post.PostId + '"  ></textarea>';
        table += '<button style = "margin-right: 10px;" type="submit"  onclick = "addComment(' + datas.post.PostId + ')" class="btn btn-primary">Ajouter</button>';
        table += '<button onclick = "annuler('+ datas.post.PostId + ')" class="btn btn-danger">Annuler</button>';
        table += '</div>';
        table += '</div>';
        table += '<button class="btn btn-success add-comment' + datas.post.PostId + '" onclick = "showComment('+ datas.post.PostId + ')">Commenter</button>';        
    }

    table += '</div>';
    table += '</div>';
    table += '</div>';
    table += '</div>';
    table += '</div>';
    table += '</div>';
    table += '</div>';

    table += '<div class="alert alert-dismissible alert-warning">';
    table += '<h4 class="alert-heading" style = "text-align: center;">';
    table += '(' + datas.post.countAnswer + 'réponse(s))</h4>';
    table += '</div>';

    if(datas.post.countAnswer > 0) {
        for(var index = 0; index < datas.post.answers.length; ++index) {
            table += '<div class="card border-info mb-3" style="max-width: 100%;">';
            table += '<div class="card-body">';
            table += '<div class = "row">';
            table += '<div class = "col-lg-1">';
            table += '<div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">';
            table += '<div class="card-body">';
            table += '<form action = "vote/voteUp/'+ datas.post.answers[index].PostId + '" method = "post">';
            table += '<button type = "submit" style="border : none; background-color : white;" name = "voteUp">';
            table += '<i class="fa fa-caret-up" style="font-size:44px; color: green"></i></button>';
            table += '</form>';
            table += '<h4 class="card-title">'+ datas.post.answers[index].score + '</h4>';
            table += '<form action = "vote/voteDown/'+ datas.post.answers[index].PostId + '" method = "post">';
            table += '<button style="border : none; background-color : white;"><i class="fa fa-caret-down" style="font-size:44px;  color: red;"></i></button>';
            table += '</form>';
            table += '<p>';
            if(datas.post.answers[index].acceptAnswer) {
                table += '<form action = "post/accept/' + datas.post.answers[index].PostId + '" method = "post">';
                table += '<i class="fa fa-check" style="font-size:16px;color:green"></i>';
                table += '<button style="border : none; background-color : white;"><i class="fa fa-times" style="font-size:16px;color:red"></i></button>';
                table += '</form>';
            }
            table += "</p>";
            table += '</div>';
            table += '</div>';
            table += '</div>';
            table += '<div class = "col-lg-8">';
            table += '<div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">';
            table += '<div class="card-body">';
            table += '<p class="card-text">' + datas.post.answers[index].Body ;
            if(datas.user && datas.user.UserId === datas.post.answers[index].AuthorId || datas.user && datas.user.Role === "admin") {
                table += '<a href="post/editPost/'+ datas.post.answers[index].PostId + '" class="card-link"><i class="fas fa-pen" style = "font-size:10px; margin-left:5px"></i></a>';
                if(datas.post.answers[index].countComment === 0) {
                    table += '<a href="post/delete/'+ datas.post.answers[index].PostId + '"  class="card-link"><i class="fas fa-trash" style = "font-size:10px; margin-left : -18px;"></i></a>';
                }
                
                if(!datas.post.answers[index].acceptAnswer) {
                    table += '<form action = "post/accept/'+ datas.post.answers[index].PostId +'" method = "post" style = "display:inline;">'
                    table += '<button style="border : none; background-color : transparent;"><span style="font-size:20px; color:#1E90FF; font-weight:bold;">&#10003;</span></button>';
                    table += '</form>'
                }
                    
            }
            table += '</p>';

            table += '<p>';
            table += '<span style = "font-size:11px">answered '+ datas.post.answers[index].Timestamp +' by <span style = "color:blue">' + datas.post.answers[index].postUser + ' </span></span>';
            
            table += '</p>';
            table += '<div>';
    
            for(var i = 0; i < datas.post.answers[index].comments.length; i++) {
                table += "<hr>";
                table += "<p><small>" + datas.post.answers[index].comments[i].Body +"</small></p>";
                table += "<p><small>par "+ datas.post.answers[index].comments[i].User +" " + datas.post.answers[index].comments[i].Timestamp + "</small>";
                if(datas.user && datas.post.answers[index].comments[i].UserId === datas.user.UserId || datas.user && datas.user.Role === "admin") {
                    table += "<small> <a href = 'comment/editComment/"+ datas.post.answers[index].comments[i].CommentId+"' title = modifier><i class='fas fa-pen'></i></a></small>" 
                    table += "<small> <a href = 'comment/delete/"+ datas.post.answers[index].comments[i].CommentId+"'title = supprimer><i class='fas fa-trash'></i></a></small>"
                }
            }
    
            if(datas.user) {
                table += '<div class="' + datas.post.answers[index].PostId + '" style = "display: none;">';
                table += '<div style = "display: flex; margin-bottom: 10px">';
                table += '<textarea style = "width:350px; margin-right: 10px; padding: 5px" class = "Body'+ datas.post.answers[index].PostId +'"></textarea>';
                table += '<button style = "margin-right: 10px;" type="submit" onclick = "addComment(' + datas.post.answers[index].PostId + ')" class="btn btn-primary">Ajouter</button>';
                table += '<button onclick = annuler(' + datas.post.answers[index].PostId + ') class="btn btn-danger">Annuler</button>';
                table += '</div>';
                table += '</div>';
                table += '<button class="btn btn-success add-comment' + datas.post.answers[index].PostId + '" onclick = "showComment(' + datas.post.answers[index].PostId + ')">Commenter</button>';
                
            }
    
            table += '</div>';
            table += '</div>';
            table += '</div>';
            table += '</div>';
            table += '</div>';
            table += '</div>';
            table += '</div>';
        }
    }

    if(datas.user) {
        table += '<div class="card border-info mb-3" style="max-width: 100%">';
        table += '<div class="card-header">Publier une reponse?</div>';
        table += '<div class="card-body">';
        table += '<form action = "post/reply/' + datas.post.PostId + '" method = "post">';
        table += '<fieldset>';
        table += '<div class="form-group">';
        table += '<textarea class="form-control" name = "Body" id="exampleTextarea" rows="4" placeholder = "Entrez votre reponse ici" value="'+ datas.post.Body + '"></textarea>';
        table += '<small id="emailHelp" class="form-text text-muted">Soyez le plus clair possible dans votre reponse</small>';
        table += '</div>';
        table += '</fieldset>';
        table += '<fieldset>';
        table += '<button type="submit" class="btn btn-primary">Publiez votre reponse</button>';
        table += '</fieldset>';
        table += '</form>';
        table += '</div>';
        table += '</div>';
    }
    table += '</div>';
    
    post.append(table);
}





