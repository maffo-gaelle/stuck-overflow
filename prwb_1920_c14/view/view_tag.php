<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tag</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type = "text/javascript"></script>
        <script src="js/tag.js" type="text/javascript"></script>
         <script src="js/menu.js" type="text/javascript"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>

    </head>
    <body>
    <?php include("menu.php") ?>
        <div class="jumbotron">
            <h1 class="display-3">All Tags</h1>
                
                <?php if($user && $user->Role == 'admin') {?>
                    <table class="table table-hover table-dark table-striped table-borderless" >
                        <thead class = "thead-light">
                            <tr>
                                <th scope="col">Tag</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr class="table-active"> 
                                    <th scope="row"><?php echo $tag->TagName; ?> <?php if($tag->getCountPostByTag() <= 1){ ?> (<a href="post/getPostByTag/<?php echo $tag->TagId ?>"><?php echo $tag->getCountPostByTag()."post"; ?></a>)<?php } else { ?>(<a href="post/getPostByTag/<?php echo $tag->TagId ?>"><?php echo $tag->getCountPostByTag()."posts"; ?></a>)<?php } ?></th>
                                    <td>
                                        <form action = 'tag/getTags/<?php echo $tag->TagId; ?>' method = 'post'>
                                            <input type="text" name ="TagName" style = "margin-right : 18px" value="<?php echo $tag->TagName; ?>" ><button type = "submit" style = "margin-right:10px; background-color:transparent; border: none"><i class='fa fa-edit' style='font-size:18px; color : #1E90FF' title="modifier"></i></button><a href="tag/delete/<?php echo $tag->TagId; ?>" style='margin-right:100px'><i class='fa fa-trash' style='font-size:18px; margin-right:20px' title="supprimer"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <form action='tag/getTags' method = 'post' id = "TagForm">
                        <input type="text" value="<?php $tag->TagName; ?>" placeholder="Ajouter un nouveau tag" id = "TagName" name = "TagName" style="width: 250px; margin-right : 5px">
                        <button type="submit" class="btn  btn-sm" title="ajouter"><span style = "font-size : 12px; color : #1E90FF; font-weight:bold;">+</span></button>
                        <?php if ($error != ""): ?>
                            <div class='erreurs'>
                                    <p><?= $error ?></p>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php } else { ?>
                    <table class="table table-hover table-dark table-striped table-borderless">
                        <thead class = "thead-light">
                            <tr>
                                <th scope="col">Tag</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr class="table-active"> <th scope="row"><?php echo $tag->TagName; ?><?php if($tag->getCountPostByTag() <= 1){ ?> (<a href="post/getPostByTag/<?php echo $tag->TagId ?>"><?php echo $tag->getCountPostByTag()."post"; ?></a>)<?php } else { ?>(<a href="post/getPostByTag/<?php echo $tag->TagId ?>"><?php echo $tag->getCountPostByTag()."posts"; ?></a>)<?php } ?></th></tr>
                            <?php endforeach; ?>
                        </tbody>
                    <?php } ?>
                </table>
            
        </div>
    </body>
</html>
