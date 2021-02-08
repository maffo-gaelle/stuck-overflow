<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>

        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="js/menu.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include("menu.php") ?>
        <div class="jumbotron" style = "">
            <form action = "comment/delete/<?php echo $comment->CommentId; ?>" method = "post" style = "text-align: center; margin-left: 25%;">
                <div class="card border-primary mb-3" style="max-width: 40rem;  padding: 10px;">
                    <div class="card-header"><i class='fas fa-trash-alt' style='font-size:48px;color:red'></i></div>
                    <div class="card-body">
                        <h4 class="card-title">êtes vous sûre de vouloir supprimer ce commentaire ?</h4>
                        <p class="card-text">Ce processus étant irréversible</p>
                    </div>
                    <div>
                        <a href="post/getpost/<?php if($post->ParentId == null) {echo $post->PostId; } else { echo $post->ParentId;}?>" type="button" class="btn btn-primary">annuler</a>
                        <button type="submit" name = "delete" class="btn btn-danger">supprimer</button>
                    </div>
                </div>
            </form>
        </div>   
    </body>
</html>