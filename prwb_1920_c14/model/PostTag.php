<?php

require_once "Post.php";
require_once "User.php";
require_once "Tag.php";

class PostTag extends Model {

    public $TagId;
    public $TagName;
    

    public function __construct($PostId, $TagId) {
        $this->PostId = $PostId;
        $this->TagId = $TagId;
        
    }

    public function insert() {       
        self::execute("INSERT INTO PostTag(PostId, TagId) VALUES(:PostId, :TagId)", array("PostId"=>$this->PostId, "TagId"=>$this->TagId));
        
        return $this;
    }

    public function delete() {
        self::execute("DELETE FROM PostTag WHERE TagId = :TagId AND PostId = :PostId", array("TagId" =>$this->TagId, "PostId"=>$this->PostId));
        
        return $this;
    }

    public function getTagNameByTagId() {
        
        return Tag::getTagByTagId($this->TagId)->TagName;
    }

    public static function validate($max_tags, $PostId){
        $query = self::execute('SELECT * FROM postTag WHERE PostId = :PostId', array("PostId"=>$PostId));
        $errorTag = "";
        if ($query->rowcount() >= $max_tags) {
            $errorTag = "Opération impossible. Le nombre maximun de tags par post est atteint";
        }

        return $errorTag;
    }

    
    public function getPostByTag() {
        $query = self::execute('SELECT FROM postTag WHERE PostId = :PostId', array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        $postTags = [];
        foreach($data as $row) {
            $postTags[] = new PostTag($row['PostId'], $row['TagId']);
        }

        return $postTags;
    }

    public static function getPostTagByPostId($postid) {
        $query = self::execute('SELECT * FROM postTag WHERE PostId = :PostId', array("PostId" => $postid));
        $data = $query->fetchAll();
        $postTags = [];
        foreach($data as $row) {
            $postTags = new PostTag($row['PostId'], $row['TagId']);
        }
        
        return $postTags;
    }

    

    public function getTagName() {
        
        return Tag::getTagByTagId($this->tagId)->TagName;
    }

    
    public static function getPostTagByPostIdAndTagId($PostId, $TagId) {

        $query = self::execute("SELECT * FROM postTag where PostId = :PostId and TagId = :TagId", array("PostId"=> $PostId, "TagId"=> $TagId)); 
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new PostTag($data['PostId'], $data['TagId']);    
        }
    }
}