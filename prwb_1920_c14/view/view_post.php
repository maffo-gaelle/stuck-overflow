<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Questions</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" type="text/css" />
        
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
         <script src="js/menu.js" type="text/javascript"></script>
        <script src="js/post.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    </head>
    <body>
        <?php include("menu.php") ?>

        <div class="jumbotron">

            <nav id = "menu-php" class="navbar navbar-expand-lg navbar-light bg-light" style= " margin-top : -50px; background-color: none!important; font-size : 1.6em; font-weight:bold; box-shadow: 20%">               
                <div class="collapse navbar-collapse" style = "bottom : 25px" > 
                    <ul class="navbar-nav mr-auto" style="margin-top: 0px">
                        <li <?php if($pageActive == "newest") { ?> class = "nav-item active" style = "text-decoration : underline" <?php } else { ?> class = "nav-item" <?php } ?>>
                            <a class="nav-link" href="post/newest">Newest <span class="sr-only">(current)</span></a>
                        </li>
                        <li <?php if($pageActive == "unanswered") { ?> class = "nav-item active" style = "text-decoration : underline" <?php } else { ?> class = "nav-item" <?php } ?>>
                            <a class="nav-link" href="post/unanswered">Unanswered</a>
                        </li>
                        <li <?php if($pageActive == "active") { ?> class = "nav-item active" style = "text-decoration : underline" <?php } else { ?> class = "nav-item" <?php } ?>>
                            <a class="nav-link" href="post/active">Active</a>
                        </li>
                        <li <?php if($pageActive == "vote") { ?> class = "nav-item active" style = "text-decoration : underline" <?php } else { ?> class = "nav-item" <?php } ?>>
                            <a class="nav-link" href="post/vote">Votes</a>
                        </li>
                        <?php if(($TagId != null)) {?>
                            <li class="nav-item active" style = "text-decoration : underline" >
                                <a class="nav-link" >Questions tagguées : <?php echo $TagName ?></a>
                            </li>
                        <?php } ?>
                        <?php if($pageActive == "searchResult"): ?>
                            <li class="nav-item active" style = "text-decoration : underline">
                                <a class="nav-link" >search results: <?php echo $search ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <form class="form-inline my-2 my-lg-0 " action = "post/search/1" method = "post">
                        <input class="form-control mr-sm-2" type="text" name = "terme" placeholder="Search" >
                        <button class="btn btn-secondary my-2 my-sm-0 btn-lg"  type="submit" >Search</button>
                    </form>
                </div>
            </nav>
            <nav id = "menu-js" class="navbar navbar-expand-lg navbar-light bg-light" style= "display:none; cursor: pointer; margin-top : -50px; background-color: none!important; font-size : 1.6em; font-weight:bold; box-shadow: 20%">               
                <div class="collapse navbar-collapse" style = "bottom : 25px" > 
                    <ul class="navbar-nav mr-auto" style="margin-top: 0px">
                        <li class="nav-item" >
                            <a class="nav-link" id = "newest">Newest <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link" id = "unanswered">Unanswered</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id = "active">Active</a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link" id = "vote">Votes</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0 "  method = "post">
                        <input class="form-control mr-sm-2" type="text" id = "search" style = "width:300px" name = "terme" placeholder="Search" >
                        <button class="btn btn-secondary my-2 my-sm-0 btn-lg" id = "btnsearch" type="submit" >Search</button>
                    </form>
                </div>
            </nav>
                    
            <div style = "margin-top: 15px;" id = "post-php">
                <?php if(count($posts) == 0) { ?>
                    <P>Aucun résultat trouvé</P>
                <?php } else { ?>
                    <?php foreach ($posts as $post): ?>
                    <?php if($post->Title != "") { ?>
                        <div class="card border-info mb-3" style="max-width: 80rem;">
                            <div class="card-header"><div><a href="post/getPost/<?php echo $post->PostId; ?>"><?php echo $post->Title; ?></a></div>
                                <div>
                                    <?php foreach ($post->getTagByPostId() as $tag) :?>
                                            <a href="post/getPostByTag/<?php echo $tag->TagId; ?>" style="background-color:#D0D0D0" ?><?php echo $tag->TagName; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"></h4>
                                <p class="card-text"><?php  echo $post->getUser()."(".$post->getScore()." vote(s), ".$post->getCountAnswers()." réponse(s)) ".$post->time(); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
               <?php } ?>            
                <div>
                    <ul class="pagination pagination-lg">
                        <?php if($prev >= 1) {?>
                            <li class="page-item"><a class="page-link" href="<?php echo $url; ?>/<?php echo $prev; ?>">&laquo;</a></li>
                        <?php } ?>
                        <?php for($p = 1; $p <= $nbPages; ++$p) : ?>
                        <li  <?php if($p == $page) echo 'class="page-item active"'; else echo 'class="page-item"'; ?>><a class="page-link" href="<?php echo $url; ?>/<?php echo $p; ?>"><?php echo $p; ?></a></li>
                        <?php endfor; ?>
                        <?php if($next <= $nbPages) {?>
                            <li class="page-item"><a class="page-link" href="<?php echo $url; ?>/<?php echo $next; ?>">&raquo;</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div id = "posts-js">

            </div>

            <div id = "posts-pagination">

            </div>

        </div>      
    </body>
</html>

