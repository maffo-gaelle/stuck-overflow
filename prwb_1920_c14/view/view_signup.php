<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type = "text/javascript"></script>
        <script src="js/signup.js" type="text/javascript"></script>
    </head>
    <body>
    <?php include("menu.php") ?>
    <div class="jumbotron">    
        <h1 class="display-3">signup</h1>
        <p class="lead">Veuillez entrer les informations suivantes :</p>
        <hr class="my-4">
            <form  action="user/signup" method="post" id= "signupForm">
                <div class="form-group">
                    <label for="UserName">Nom d'utilisateur </label>
                    <input type="text" class="form-control" id="UserName" placeholder="Entrez pseudo" name="UserName" value="<?= $UserName ?>">
                    <?php if($errorUserNameUnique): ?>
                        <div class='erreurs'>
                            <?= $errorUserNameUnique ?>
                        </div>
                    <?php endif; ?>
                    <?php if (count($errorsUserName) != 0): ?>
                        <div class='erreurs'>
                            <ul>
                                <?php foreach ($errorsUserName as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="FullName">Nom complet</label>
                    <input type="text" class="form-control" id="FullName" placeholder="Entrez votre nom et prénom" name="FullName" value="<?= $FullName ?>">
                    <?php if (count($errorsFullName) != 0): ?>
                        <div class='erreurs'>
                            <ul>
                                <?php foreach ($errorsFullName as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" class="form-control" id="Password" placeholder="Entrez votre mot de passe" name = "Password" value="<?= $Password ?>">
                    <small id="emailHelp" class="form-text text-muted">doit avoir au minimum une longueur de 8 caractères, doit contenir au moins un chiffre, une lettre majuscule et un caractère non alphanumérique</small>
                    <?php if (count($errorsPassword) != 0): ?>
                        <div class='erreurs'>
                            <ul>
                                <?php foreach ($errorsPassword as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password_confirm">Password</label>
                    <input type="password" class="form-control" id="password_confirm" placeholder="Veuillez confirmer votre mot de passe" name="password_confirm">
                    <?php if($errorPassword_confirm): ?>
                        <div class='erreurs'>
                            <?= $errorPassword_confirm ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" id="Email" placeholder="Entrez votre email" name="Email" value="<?= $Email ?>">
                    <?php if (count($errorsEmail) != 0): ?>
                        <div class='erreurs'>
                            <ul>
                                <?php foreach ($errorsEmail as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if($errorEmailUnique): ?>
                        <div class='erreurs'>
                            <?= $errorEmailUnique ?>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="lead">
                <button class="btn btn-primary btn-lg" type="submit" role="button">Inscription</button>
                </p>
            </form>
            

        </div>
        
    </body>
</html>