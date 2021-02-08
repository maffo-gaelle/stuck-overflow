<?php

require_once 'model/Comment.php';
require_once 'model/Post.php';
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerComment extends Controller {
    public function index() {
    }

    public function addComment() {
        $user = $this->get_user_or_false();
        $Timestamp = date("Y-m-d H:i:s");
        $UserId = $user->UserId;
        $Body = "";
        $comment = null;
        $post = null;
        $ok = false;
        $comment = null;
        $questionId = "";
        $error = "";
        $PostId = $_GET['param1'];
        $post = Post::getPostByPostId($PostId);
        if($post->ParentId == null) {
            $questionId = $PostId;
        } else {
            $questionId = $post->ParentId;
        }

        if(isset($_GET['param1']) && isset($_POST['Body']) && $_POST['Body'] != "") {
            
            $Body = $_POST['Body'];

            $error = Comment::validate($Body);
            if($error == "") {
                $comment = new Comment($UserId, $PostId, $Body, $Timestamp);
                $comment->update();
            }           
        }
        $this->redirect("post", "getPost", $questionId, $PostId);
    }

    public function editComment() {
        $user = $this->get_user_or_false();
        $Body = "";
        $Timestamp = date("Y-m-d H:i:s");
        $comment = "";
        $questionId = "";

        if(isset($_GET['param1'])) {
            $CommentId = $_GET['param1'];
            $comment = Comment::getCommentByCommentId($CommentId);
            $Body = $comment->Body;
            $post = Post::getPostByPostId($comment->PostId);
            if($post->ParentId == null) {
                $questionId = $post->PostId;
            } else {
                $questionId = $post->ParentId;
            }
            
            if(isset($_GET['param1']) && isset($_POST['Body'])) {
            
                $comment->Body = $_POST['Body'];
                $comment->Timestamp =$Timestamp;   

                $comment->update();

                $this->redirect("post", "getPost", $questionId);
            }
        
        }
        (new View('editComment'))->show(array("Body"=>$Body, "post"=>$post, "user" => $user, 'comment'=>$comment));    
    }

    public function delete() {
        $user = $this->get_user_or_false();
        $questionId = "";
        $post = null;

        if(isset($_GET['param1'])) {
            $CommentId = $_GET['param1'];
            $comment = Comment::getCommentByCommentId($CommentId);
            $postId = $comment->PostId;
            $post = Post::getPostByPostId($postId);
            if($post->ParentId == null) {
                $questionId = $postId;
            } else {
                $questionId = $post->ParentId;
            }

            if(isset($_POST['delete'])) {

                $comment->delete();
                $this->redirect("post", "getPost", $questionId);
            }
        }
        (new view('deleteComment'))->show(array("user"=>$user, "comment"=>$comment, "post"=>$post));
    }

    

    public function getCommentsJson() {
        $user = $this->get_user_or_false();
        $postId = $_GET['param1'];
        $post = Post::getPostByPostId($postId);
        $comments = $post->getComment();
        foreach($comments as $comment) {
            $comment->User = $comment->getUser();
            $comment->Timestamp = $comment->time();
        }

        $data = [];
        $data['user'] = $user;
        $data['comments'] = $comments; 
        
        echo json_encode($data);
    }

    public function addCommentJson() {
        $user = $this->get_user_or_false();
        $Timestamp = date("Y-m-d H:i:s");
        $UserId = $user->UserId;

        if(isset($_POST['Body']) && $_POST['Body'] != "" && isset($_POST['PostId']) && $_POST['PostId'] != "") {
            $Body = $_POST['Body'];
            $PostId = $_POST['PostId'];

            $post = Post::getPostByPostId($PostId);
            if($post->ParentId == null) {
                $questionId = $PostId;
            } else {
                $questionId = $post->ParentId;
            }

            $comment = new Comment($UserId, $PostId, $Body, $Timestamp);
            $comment->update();
            $this->redirect("post", "getPostJson", $questionId);
        }  
    }

}