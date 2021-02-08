<?php

require_once "Post.php";
require_once "User.php";

class Comment extends Model {

    public $CommentId;
    public $UserId;
    public $PostId;
    public $Body;
    public $Timestamp;

    public function __construct($UserId, $PostId, $Body, $Timestamp, $CommentId = "-1") {
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->CommentId = $CommentId;
    }

    public function update() {
        if(self::getCommentByCommentId($this->CommentId)) {
            self::execute("UPDATE comment SET Body=:Body, Timestamp = :Timestamp WHERE CommentId = :CommentId", 
            array("Body"=>$this->Body, "Timestamp"=>$this->Timestamp, "CommentId" => $this->CommentId));
        } else {
            self::execute("INSERT INTO comment(UserId, PostId, Body, Timestamp) VALUES(:UserId, :PostId, :Body, :Timestamp)", 
            array("UserId"=>$this->UserId, "PostId"=>$this->PostId, "Body"=>$this->Body, "Timestamp"=>$this->Timestamp));
        }

        return $this;
    }

    public function delete() {
        self::execute("DELETE FROM comment where CommentId = :CommentId", array("CommentId" => $this->CommentId));
        
        return $this;
    }

    public function getUser() {
        
        return User::getUserByUserId($this->UserId)->FullName;
    }

    public function getPost() {
        
        return Post::getPostByPostId($this->PostId);
    }

    public static function validate($Body) {
        $error = "";
  
        if (!(isset($Body) && is_string($Body) && strlen($Body) > 0)) {
            $error = "Un texte est requis.";
        }
        
        return $error;
    }

    public static function getCommentByCommentId($CommentId) {
        $query = self::execute("SELECT * FROM comment where CommentId = :CommentId ", array("CommentId" => $CommentId ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Comment($data['UserId'], $data['PostId'], $data['Body'], $data['Timestamp'],  $data['CommentId']);
        }
    }  

    public function time($full = false) {

        $now = new DateTime;

        $ago = new DateTime($this->Timestamp);

        $diff = $now->diff($ago);



        $diff->w = floor($diff->d / 7);

        $diff->d -= $diff->w * 7;



        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {

            if ($diff->$k) {

                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {

                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


}   