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

        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="js/menu.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include("menu.php") ?>
        <div class="jumbotron">
        <?php if($user) {?>
                <div class="card border-info mb-3" style="max-width: 100%">
                <div class="card-header">Ajoutez votre commentaire</div>
                <div class="card-body">
                <?php if($user) {?>
                    <form action = "comment/editComment/<?php echo $comment->CommentId; ?>" method = "post">
                        <fieldset>
                            <div class="form-group">
                                <textarea class="form-control" name = "Body" rows="4" ><?php echo $comment->Body?></textarea>
                                <small id="emailHelp" class="form-text text-muted">Soit pour demander des éclaircissements à l'auteur, laisser une critique constructive ou encore ajouter des informations pertinentes</small>
                            </div>
                        <fieldset>
                        </fieldset>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                            <a href="post/getPost/<?php if($post->ParentId == null) {echo $post->PostId;} else {echo $post->ParentId;} ?>" class="btn btn-primary">annuler</a>
                        </fieldset>
                    </form>
                <?php } ?>
                </div>
                </div>
            <?php } ?>
        </div>