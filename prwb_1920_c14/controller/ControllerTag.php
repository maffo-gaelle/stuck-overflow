<?php

require_once 'model/Tag.php';
require_once 'model/Post.php';
require_once 'model/User.php';
require_once 'model/PostTag.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTag extends Controller {
    public function index() {
    }

    
    public function getTags() {
        $user = $this->get_user_or_false();
        $tags = Tag::getTags();
        $error = "";
        $tag = null;

        if(isset($_GET['param1']) && isset($_POST['TagName'])) {
            
            $TagId = $_GET['param1'];
            $TagName = $_POST['TagName'];
            $error = Tag::validate($TagName);
            if($error == "") {
                $tag = Tag::getTagByTagId($TagId);
                $tag->TagName = $TagName;
                $tag->update();
                $this->redirect("tag", "getTags");
            }
        }
        
        if(isset($_POST['TagName'])) {
            $TagName = $_POST['TagName'];
            $error = Tag::validate($TagName);
            if($error == "") {
                $tag = new Tag($TagName);
                $tag->insert();
                $this->redirect("tag", "getTags");
            }
        }
        (new View("tag"))->show(array("tags" => $tags, "user" => $user, "error" => $error));
    }
   
    public function delete() {
        $user = $this->get_user_or_false();

        if(isset($_GET['param1'])) {

            $TagId = $_GET['param1'];
            $tag = Tag::getTagByTagId($TagId);

            if(isset($_POST['delete'])) {
                $tags = $tag->ByTag();
                var_dump($tags);
                foreach($tags as $posttag) {
                    $posttag->delete();
                }
                $tag->delete();
                $this->redirect("tag", "getTags");
            }
            
        }
        (new View ("deleteTag"))->show(array("tag" =>$tag, "user"=>$user));
    }

    public function byTag() {
        $user = $this->get_user_or_false();
        $posts = [];
        
        if(isset($_GET['param1'])) {
            $TagId = $_GET['param1'];
            $tag = Tag::getTagBYTagId($TagId);
            $tagName = $tag->TagName;

            $posts = $tag->byTag();
            
        }
        (new View("post"))->show(array("posts" => $posts, "user" =>$user));
    }

    public function tag_available_service() {
        $res = "true";

        if(isset($_POST['TagName']) && $_POST['TagName'] != "") {
            $TagName = $_POST['TagName'];
            $tag = Tag::getTagByTagName($TagName);
            if($tag) {
                $res = "false";
            }
        }
        echo $res;
    }
}

    