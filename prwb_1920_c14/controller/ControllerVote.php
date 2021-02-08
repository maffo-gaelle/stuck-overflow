<?php

require_once 'framework/Controller.php';
require_once 'model/User.php';
require_once 'model/Post.php';
require_once 'model/Vote.php';
require_once 'framework/View.php';


class ControllerVote extends Controller {
    public function index() {
        
    }

    public function voteUp() { 
        $user = $this->get_user_or_false();

        if(isset($_GET['param1'])) {
            $PostId = $_GET['param1'];
            $UserId = $user->UserId;
            $post = Post::getPostByPostId($PostId);
            $parentid = $post->ParentId;
            $vote = Vote::getVoteByUserIdAndPostId($UserId, $PostId);

            if(!($vote)) {
                $vote = new Vote($UserId, $PostId, 1);
                $vote->Update();
            } else {
                $vote->delete();
            }
        
            if($parentid == null) {
                $questionId = $PostId;
            } else {
                $questionId = $post->ParentId;
            }
        }
        $this->redirect("post", "getPost", $questionId);
    }

    public function voteDown() {
        $user = $this->get_user_or_false();
        $post = null;
        
        if(isset($_GET['param1'])) {
            $PostId = $_GET['param1'];
            $UserId = $user->UserId;
            $post = Post::getPostByPostId($PostId);
            $parentid = $post->ParentId;
            $vote = Vote::getVoteByUserIdAndPostId($UserId, $PostId);

            if(!($vote)) {
                $vote = new Vote($UserId, $PostId, -1);
                $vote->update();
            } else {
                $vote->delete();
            }
        
            if($parentid == null) {
                $questionId = $PostId;
            } else {
                $questionId = $post->ParentId;
            }
        }

        $this->redirect("post", "getPost", $questionId);
    }
}