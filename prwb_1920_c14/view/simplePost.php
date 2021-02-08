

<div class="card border-info mb-3" style="max-width: 100%;">
    <div class="card-body">
        <div class = "row">
            <div class = "col-lg-1">
                <div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">
                    <div class="card-body">
                        <?php if($user): ?>
                            <form action = "vote/voteUp/<?php echo $post->PostId; ?>" method = "post">
                                <button type = "submit" style="border : none; background-color : white;" name = "voteUp"><i class="fa fa-caret-up" style="font-size:44px; color: green"></i></button>
                            </form>
                        <?php endif; ?>
                            <h4 class="card-title"><?php echo $post->getScore(); ?></h4>
                            <?php if($user): ?>
                                <form action = "vote/voteDown/<?php echo $post->PostId; ?>" method = "post">
                                    <button style="border : none; background-color : white;"><i class="fa fa-caret-down" style="font-size:44px;  color: red;"></i></button>
                                </form>
                            <?php endif; ?>
                        <p>
                        
                        <?php if($post->getAcceptedAnswer() && $post->ParentId != null || $post->ParentId != null && $post->getAcceptedAnswer() ) {?>
                            <i class="fa fa-check" style="font-size:16px;color:green"></i>
                        <?php }?>   
                            <?php if($user && $post->getAcceptedAnswer() && $user->UserId == $post->AuthorId && $post->ParentId != null || $user && $post->ParentId != null && $post->getAcceptedAnswer() && $user->Role== "admin" ) {?>
                            <form action = "post/accept/<?php echo $post->PostId; ?>" method = "post">
                                <button style="border : none; background-color : white;"><i class="fa fa-times" style="font-size:16px;color:red"></i></button>
                            </form>
                        <?php }?>
                        </p>
                    </div>
                </div>
            </div>
            <div class = "col-lg-7">
                <div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">
                    <div class="card-body">
                        <p class="card-text"><?php echo $post->Body; ?>
                        
                        <?php if(($user) && ($user->UserId) == ($post->AuthorId) || ($user) && ($user->Role == "admin")){ ?>
                            <a href="post/editPost/<?php echo $post->PostId; ?>" class="card-link"><i class='fa fa-edit' style='font-size:18px' title="modifier"></i></a>
                            <?php if(($post->ParentId == null && $post->getCountAnswers() == 0 && $post->countComment() == 0) || $post->ParentId != null && $post->countComment() == 0){?>
                                <a href="post/delete/<?= $post->PostId; ?>"  class="card-link"><i class='fa fa-trash' style='font-size:18px;margin-left : -20px' title="supprimer"></i></a>
                            <?php } ?>
                            <p style = " display:inline">
                            <?php if(!($post->ParentId == null || $post->getAcceptedAnswer())) {?>
                                <form action = "post/accept/<?php echo $post->PostId; ?>" method = "post" style = " display:inline-block !important;">
                                    <button type = "submit" style="border : none; background-color : white;"><i class="fa fa-check-circle-o" style="font-size:18px;color:#1E90FF; display:inline;" ></i></button>
                                </form>
                            <?php } ?>
                            </p>
                        </p>
                        <?php }?>
                        
                        <div>
                            <?php $comments = $post->getComment();?>
                            <?php foreach ($comments as $comment): ?>
                                <hr>
                                <p><small><?php echo $comment->Body; ?></small></p>
                                <p><small>par <?php echo $comment->getUser()." ". $comment->time(); ?> </small>
                                <small><?php if($user && $comment->UserId == $user->UserId || $user && $user->Role == "admin") {?><a href="comment/editComment/<?php echo $comment->CommentId?>" title="modifier"><i class='fa fa-edit' style='font-size:12px' title="modifier"></i></a> <?php } ?> </small>
                                <small><?php if($user && $comment->UserId == $user->UserId || $user && $user->Role == "admin") {?><a href="comment/delete/<?php echo $comment->CommentId?>" title="supprimer"><i class='fa fa-trash' style='font-size:12px' title="supprimer"></i></a> <?php } ?> </small></p>

                            <?php endforeach; ?>
                        </div>
                        <div class = "openComment-js">
                            
                        </div>
                        <div class = "openComment-php">
                            <?php if($user && $openComment == $post->PostId) {?>
                                <form action = "comment/addComment/<?php echo $post->PostId; ?>" method = "post">
                                    <fieldset>
                                        <div class="form-group">
                                            <textarea class="form-control" name = "Body" rows="2" ></textarea>
                                            <small id="emailHelp" class="form-text text-muted">Soit pour demander des éclaircissements à l'auteur, laisser une critique constructive ou encore ajouter des informations pertinentes</small>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <button type="submit" class="btn btn-primary ">Ajouter</button>
                                        <a href="post/getPost/<?php if($post->ParentId == null) {echo $post->PostId;} else {echo $post->ParentId;} ?>" class="btn btn-primary">annuler</a>
                                    </fieldset>
                                </form>
                            <?php } ?>                           
                            <?php if($user && $openComment != $post->PostId) { ?>
                                <a id = "openComment" href="post/getPost/<?php if($post->ParentId == null) { echo $post->PostId; } else { echo $post->ParentId; } ?>/<?php echo $post->PostId; ?>" class="card-link">ajouter un commentaire</a>
                            <?php } ?> 
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class = "col-lg-4">
                <div class="card border-info mb-3" style="max-width: 100%; border: none; box-shadow: none;">
                    <div class="card-header">asked <?php echo $post->time(); ?></div>
                    <div class="card-header">
                        <?php if($post->ParentId == null) { ?>
                            <?php foreach($tags as $tag): ?>
                                <span style="background-color:#D0D0D0; margin-right : 7px" ><a href="post/getPostByTag/<?php echo $tag->TagId ?>" ><?php echo $tag->TagName; ?></a> 
                                <?php if($user && $post->ParentId == null && $user->Role == "admin" || $user && $post->ParentId == null && $user->UserId == $post->AuthorId): ?>
                                    <a href="post/deleteTagPost/<?php echo $tag->TagId; ?>/<?php echo $post->PostId; ?>"><i class="fa fa-remove" style = "margin-right : 3px"></i></a>
                                <?php endif; ?>
                                </span>

                            <?php endforeach; ?>
                            <?php if($user && $post->ParentId == null && $user->Role == "admin" || $user && $post->ParentId == null && $user->UserId == $post->AuthorId): ?>
                                <form action="post/addPostTag/<?php echo $post->PostId; ?>" method="post" class="col-md-4" style = "display : inline">
                                
                                        <select name="tagId" class="custom-select" style ="width : 60px; margin-right : 10px">
                                            <?php foreach($allTags as $tag) : ?>
                                            <?php if(!in_array($tag->TagId,  $tagIds)) {?>
                                                <option value="<?php echo $tag->TagId; ?>"><?php echo $tag->TagName; ?></option>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                    <button type='submit' value='' style = "display:inline"> + </button>
                                </form> 
                            <?php endif; ?>
                            <?php if(count($errorTag) != 0):?>
                                <ul>
                                    <?php foreach($errorTag as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php } ?>
                        
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><img src="upload/unknow.png" style = "width: 30px; height: 30px; border-radius: 50%;" alt="picturepath"></h4>
                        <p class="card-text"><?php  echo $post->getUser(); ?></p>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>