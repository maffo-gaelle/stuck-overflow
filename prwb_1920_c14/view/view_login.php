<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type = "text/javascript"></script>
        <script src="js/login.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include("menu.php") ?>
        <div class="jumbotron">
            <h1 class="display-3">LOGIN</h1>
            <form method="post" action="user/login" id= "loginForm">
                <div class="form-group">
                    <label for="UserName">nom d'utilisateur</label>
                    <input type="text" class="form-control" id="UserName" name ="UserName" value = "<?=$UserName ?>" placeholder="Entrez votre pseudo">
                    <?php if($errorUserName): ?>
                        <div class='erreurs'>
                            <?= $errorUserName ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" class="form-control" id="Password" name ="Password" value = "<?=$Password ?>"  placeholder="Entrez votre mot de passe">
                    <?php if($errorPassword): ?>
                        <div class='erreurs'>
                            <?= $errorPassword ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <p class="lead">
                <button class="btn btn-primary btn-lg" type="submit" role="button"><div class="btn-login"> </div> Connexion</button>
                    
                    
                </p>
            </form>
    </body>
</html>
