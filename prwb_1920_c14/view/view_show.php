<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Question n°<?php if($post->ParentId == null) {echo $post->PostId;} else {echo $post->ParentId;} ?></title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" type="text/css" />
        
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type = "text/javascript"></script>
        <script src="js/showPost.js" type="text/javascript"></script>
        <script src="js/menu.js" type="text/javascript"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    </head>
    <body>
        <?php include("menu.php") ?>
        <input type="text"  class = "PostId" value="<?php echo $post->PostId;?>" hidden>
        
        <div class="jumbotron" id="post-php">
            <h1 class="display-3"><?=  $post->Title ?></h1>
            <?php include("simplePost.php") ?>
            
            <div class="alert alert-dismissible alert-warning">
                <h4 class="alert-heading" style = "text-align: center;"><?php if($post->ParentId == null) {?>(<?php echo $post->getCountAnswers(); ?> réponses)<?php } ?></h4>
            </div>
            
            <?php foreach ($post->getAnswers() as $post): ?>
          
                <?php include("simplePost.php") ?>
            <?php endforeach; ?>

            <?php if($user) {?>
                <div class="card border-info mb-3" style="max-width: 100%">
                <div class="card-header">Publier une reponse?</div>
                <div class="card-body">
                <form action = "post/reply/<?php if($post->ParentId == null) {echo $post->PostId;} else {echo $post->ParentId;} ?>" method = "post">
                    <fieldset>
                    
                        <div class="form-group">
                            <textarea class="form-control" name = "Body" id="exampleTextarea" rows="4" placeholder = "Entrez votre reponse ici" value="<?= $post->Body ?>"></textarea>
                            <small id="emailHelp" class="form-text text-muted">Soyez le plus clair possible dans votre reponse</small>
                        </div>
                    </fieldset>
                    <fieldset>
                        <button type="submit" class="btn btn-primary">Publiez votre reponse</button>
                    </fieldset>
                </form>
                </div>
                </div>
            <?php } ?>

         </div>   

         <div class="jumbotron" id="post-js">

         </div>
    </body>
    
</html>
