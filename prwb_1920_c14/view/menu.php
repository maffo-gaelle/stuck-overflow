<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <a class="navbar-brand" href="post/newest">Stuck-overflow</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav ">
              <?php if(!$user){ ?>
              <li class="nav-item">
                  <a class="nav-link" href="user/signup">Inscription</a>
              </li>
              <li class="nav-item pull-right">
                  <a class="nav-link " href="user/login">Connexion</a>
              </li>
              
              <?php }else{?>
                <li class="nav-item" style = "margin-right:100">
                  <em class="nav-link"  ></i><?php echo $user->UserName." 's connect"; ?></em>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="user/logout">Déconnexion</i></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="post/askQuestion">Poser une question</a>
                </li>
              <?php }?>
              <li class="nav-item">
                  <a class="nav-link " href="tag/getTags" style = "margin-left:100">Tag</a>
              </li>  
              <li class="nav-item" id="statistique-js" style="display:none">
                  <a class="nav-link " href="user/showGraph">Stat</a>
              </li> 
                     
          </ul>
          
      </div>
      
  </nav>