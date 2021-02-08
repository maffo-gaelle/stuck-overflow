<!DOCTYPE html>
 <html>
     <head>
         <meta charset="UTF-8">
         <title>Poser une question</title>
         <base href="<?= $web_root ?>"/>
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
         <link href="css/login.css" rel="stylesheet" type="text/css"/>

         <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
         <script src="js/menu.js" type="text/javascript"></script>
     </head>
     <body>
         <?php include("menu.php") ?>
         <div class="jumbotron">
             <h1 class="display-3">Poser une question</h1>
             <form action = "post/askQuestion" method = "post">
             <fieldset>
                 <div class="form-group row">
                 <div class="col-sm-10">
                     <input name = "Title" value="<?= $Title ?>" type="text" class="form-control-plaintext" placeholder="Entrez un titre" >
                     <small  class="form-text text-muted">soyez précis et imaginez que vous posez une question à une autre personne.</small>
                     <?php if($errorTitle): ?>
                         <div class='erreurs'>
                             <?= $errorTitle ?>
                         </div>
                     <?php endif; ?>
                 </div>
             </fieldset>
             <fieldset>
                <div class="custom-control custom-checkbox">
                    <?php foreach($tags as $tag) : ?>
                        <span>
                            <input type="checkbox" class="custom-control-input" id="<?php echo $tag->TagId; ?>" name = "choix[]" value = "<?php echo $tag->TagId; ?>">
                            <label class="custom-control-label" for="<?php echo $tag->TagId; ?>" style="margin-right:50px" ><?php echo $tag->TagName; ?></label>
                        </span>
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
                     <textarea class="form-control" name = "Body" value="<?= $Body ?>" rows="10" placeholder = "Entrez votre question..."></textarea>
                     <small  class="form-text text-muted">inclure toutes les informations dont on aura besoin pour répondre à votre question</small>
                     <?php if($errorBody): ?>
                         <div class='erreurs'>
                             <?= $errorBody ?>
                         </div>
                     <?php endif; ?>
                 </div>
             <fieldset>
             </fieldset>
                 <button type="submit" class="btn btn-primary">Publier votre question</button>
             </fieldset>
             </form>
         </div>
     </body>
 </html>

