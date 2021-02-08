<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/askQuestion.css" rel="stylesheet" type="text/css"/>

        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="js/menu.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include("menu.php") ?>
        <div class="jumbotron">
            <h1 class="display-3">Modifier le post</h1>
            <form action = "post/editPost/<?= $post->PostId ?>" method = "post">
                <?php if($post->ParentId == null) {?>
                    <fieldset>
                        <div class="form-group row">
                        <!--<div class="col-sm-10">-->
                            <input name = "Title" value="<?php echo $Title; ?>" type="text" class="form-control-plaintext" placeholder="Entrez un titre" >
                            <small  class="form-text text-muted">soyez précis et imaginez que vous posez une question à une autre personne.</small>
                            <?php if($errorTitle): ?>
                            <div class = 'erreurs'>
                                <?= "Vous ne pouvez pas selectionner plus de 3 tags" ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="custom-control custom-checkbox">
                        <?php foreach($tags as $tag) : ?>
                            
                                <?php if(in_array($tag->TagId,  $tagIds)) {?>
                                    <span>
                                        <input type="checkbox" class="custom-control-input" id="<?php echo $tag->TagId; ?>" name = "choix[]" value = "<?php echo $tag->TagId; ?>" checked >
                                        <label class="custom-control-label" for="<?php echo $tag->TagId; ?>" style="margin-right:50px" ><?php echo $tag->TagName; ?></label>
                                    </span>
                                <?php } else { ?>
                                    <span>
                                        <input type="checkbox" class="custom-control-input" id="<?php echo $tag->TagId; ?>" name = "choix[]" value = "<?php echo $tag->TagId; ?>">
                                        <label class="custom-control-label" for="<?php echo $tag->TagId; ?>" style="margin-right:50px" ><?php echo $tag->TagName; ?></label>
                                    </span>
                                <?php } ?>
                        <?php endforeach ?>
                        <?php if($errorTag): ?>
                            <div class = 'erreurs'>
                                <?= "Vous ne pouvez pas selectionner plus de 3 tags" ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    </fieldset>
                  
                    <fieldset>
                        <div class="form-group">
                            <textarea class="form-control" name = "Body" value = "<?= $Body; ?>" rows="10" placeholder = "Entrez votre question..."><?= $Body; ?></textarea>
                            <small  class="form-text text-muted">inclure toutes les informations dont on aura besoin pour répondre à votre question</small>
                            <?php if($errorBody): ?>
                            <div class = 'erreurs'>
                                <?= "Un texte est requis." ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </fieldset>
                                    
                <?php } else {?>
                    <fieldset>
                        <div class="form-group">
                            <textarea class="form-control" name = "Body" value = "<?= $Body; ?>" rows="10" placeholder = "Entrez votre question..."><?= $Body; ?></textarea>
                            <small  class="form-text text-muted">inclure toutes les informations dont on aura besoin pour répondre à votre question</small>
                        </div>
                    </fieldset>
                <?php } ?>

                <fieldset>
                    <button type="submit" class="btn btn-primary" role ="button">modifier</button>
                    <a href="post/getPost/<?php if($post->ParentId == null) {echo $post->PostId; } else { echo $post->ParentId;}?>" class="btn btn-primary">annuler</a>
                </fieldset>
            </form>
        </div>
    </body>
</html>
