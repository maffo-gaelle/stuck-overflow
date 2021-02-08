<?php

require_once "Post.php";
require_once "User.php";
require_once "PostTag.php";

class Tag extends Model {

    public $TagId;
    public $TagName;
    

    public function __construct($TagName, $TagId = "-1") {
        $this->TagId = $TagId;
        $this->TagName = $TagName;
        
    }

    public function insert() {

        self::execute("INSERT INTO tag(TagName) VALUES(:TagName)", array("TagName"=>$this->TagName));

        return $this;
    }

    public function update() {

        self::execute("UPDATE tag SET TagName = :TagName WHERE TagId = :TagId", array("TagName"=>$this->TagName ,"TagId"=>$this->TagId));

        return $this;
    }
    
    public function delete() {
        self::execute("DELETE FROM tag where TagId = :TagId", array("TagId"=> $this->TagId));

        return $this;
    }

    public static function getTags() {

        $query = self::execute("SELECT * FROM tag  ORDER BY TagName", array());
        $data = $query->fetchAll();
        $tags = [];
        foreach($data as $row) {
            $tags[] = new Tag($row['TagName'], $row['TagId']);
        }

        return $tags;
    }


    public static function validate($TagName){
        $error = "";
        $tag = self::getTagByTagName($TagName);
        if ($tag) {
            $error = "Ce tag existe déjà.";
        } 

        return $error;
    }

    public static function getTagByTagName($TagName) {
        $query = self::execute("SELECT * FROM tag where TagName = :TagName ", array("TagName" => $TagName));
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Tag($data['TagName'], $data['TagId']);
        }
    }

    public function getCountPostByTag() {

        $query = self::execute('SELECT * FROM postTag where TagId = :TagId', array("TagId" => $this->TagId));

        return $query->rowcount();
    }

    public function ByTag() {
        
        $query = self::execute('SELECT * FROM postTag where TagId = :TagId', array("TagId" => $this->TagId));
        $data = $query->fetchAll();
        $posts = [];
        foreach($data as $row) {
            $posts[] = new PostTag($row['PostId'], $row['TagId']);
        }
        
        return $posts;
    }

    public static function getTagByTagId($TagId) {
        $query = self::execute("SELECT * FROM tag where TagId = :TagId ", array("TagId" => $TagId ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Tag($data['TagName'], $data['TagId']);
        }
    }
    
}
    