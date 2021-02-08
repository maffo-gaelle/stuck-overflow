<?php


require_once "User.php";
require_once "Tag.php";
require_once "Vote.php";
require_once "Comment.php";

class Post extends Model {

    public $AuthorId;
    public $Title;
    public $Body;
    public $Timestamp;
    public $AcceptedAnswerId;
    public $ParentId;
    public $PostId;

    public function __construct($AuthorId, $Title, $Body, $Timestamp, $AcceptedAnswerId, $ParentId, $PostId = "-1") {
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->AcceptedAnswerId = $AcceptedAnswerId;
        $this->ParentId = $ParentId;
        $this->PostId = $PostId;
    }
    /*
     *Cette méthode permet de modifier ou d'inserer un post dans la db
     * 
     * @Return Post
     */
    public function update() {
        if(self::getPostByPostId($this->PostId)) {
            self::execute("UPDATE post SET Title =:Title, Body=:Body, Timestamp = :Timestamp, AcceptedAnswerId = :AcceptedAnswerId WHERE PostId = :PostId", 
            array("Title"=>$this->Title, "Body"=>$this->Body, "Timestamp"=>$this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "PostId" => $this->PostId));
        } else {
            self::execute("INSERT INTO post(AuthorId, Title, Body, Timestamp, AcceptedAnswerId, ParentId) VALUES(:AuthorId,:Title,:Body,:Timestamp,:AcceptedAnswerId, :ParentId)", 
            array("AuthorId"=>$this->AuthorId, "Title"=>$this->Title, "Body"=>$this->Body, "Timestamp"=>$this->Timestamp,"AcceptedAnswerId"=>$this->AcceptedAnswerId, "ParentId"=>$this->ParentId));
        }

        return $this;
    }
  
    /*
     *Cette méthode permet de supprimer un post dans la db
     * 
     * @Return void
     */
    public function delete() {
        var_dump($this->PostId);
        self::execute("DELETE FROM post where PostId = :PostId", array("PostId" => $this->PostId));
        return $this;
        var_dump("ok");
    }

  
    /*
     *Cette méthode permet d'obtenir un tableau de réponses dans la db
     * 
     * @Return Post []
     * 
     */
    public function getAnswers() {
        $query = self::execute('SELECT * FROM post p where ParentId = :PostId
        ORDER BY 
        (SELECT PostId WHERE p.PostId = :AcceptedAnswerId) DESC,
        (SELECT ifnull(SUM(UpDown), 0) from vote where vote.PostId = p.PostId) DESC,
        Timestamp DESC', array("PostId" => $this->PostId, "AcceptedAnswerId" => $this->AcceptedAnswerId));
        
        $data = $query->fetchAll();
        $answers = [];
        foreach($data as $row) {
            $answers[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], ($row['Timestamp']), $row['AcceptedAnswerId'], $row['ParentId'], $row['PostId']);
        }
        return $answers;
    }

    public function getComment() {
        $query = self::execute('SELECT * FROM comment where PostId = :PostId ORDER BY Timestamp DESC', array("PostId" => $this->PostId));
        
        $data = $query->fetchAll();
        $comments = [];
        foreach($data as $row) {
            $comments[] = new Comment($row['UserId'], $row['PostId'], $row['Body'], $row['Timestamp'], $row['CommentId']);
        }
        
        return $comments;
    }

    public function countComment() {
        $query = self::execute('SELECT * FROM comment where PostId = :PostId ORDER BY Timestamp DESC', array("PostId" => $this->PostId));
        
        return $query->rowCount();
    }


    public function getAcceptedAnswer() {

        if($this->ParentId != null) {
            $post = Post::getPostByPostId($this->ParentId);
            if($post->AcceptedAnswerId == $this->PostId) {
                return true;
            }
            return false;
        }
    }


    /*
     *Cette méthode permet d'obtenir de nombre de réponse sur une question
     * 
     * @Return int
     * 
     */
    public function getCountAnswers() {
        $query = self::execute('SELECT * FROM post where ParentId = :PostId', array("PostId" => $this->PostId));
        return $query->rowcount();
    }

    /*
     *Cette méthode permet d'obtenir l'auteur d'un Post
     * 
     * @Return User
     * 
     */
    public function getUser() {
        return User::getUserByUserId($this->AuthorId)->FullName;
    }

    /*
     *Cette méthode permet d'obtenir le score d'un post
     * 
     * @Return int
     * 
     */
    public function getScore() {
        $query = self::execute('SELECT ifnull(SUM(UpDown), 0) score FROM vote where PostId = :PostId', array("PostId" => $this->PostId));
        $data = $query->fetch();
        return $data['score'];
    }

    public static function validate($Title, $Body) {
        $errors['Title'] = "";
        $errors['Body'] = "";

        if (!(isset($Title) && is_string($Title) && strlen($Title) > 0)) {
            $errors['Title'] = "Un titre est requis";
        }
        if (!(isset($Body) && is_string($Body) && strlen($Body) > 0)) {
            $errors['Body'] = "Le texte est vide.";
        }
        return $errors;
    }

    public static function search($terme, $limit, $offset) {
        
        $select_terme = self::execute("SELECT * FROM post WHERE ( Title like :Title or Body like :Body) order by Timestamp DESC LIMIT $limit OFFSET $offset", array("Title" => "%".$terme."%", "Body" => "%".$terme."%"));
        $data = $select_terme->fetchAll();
        $posts = [];
        foreach ($data as $row) {
            if($row['ParentId'] != null) {
                $post = Post::getPostByPostId($row['ParentId']);
                $posts[] = $post;
            } else {
                $posts[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
            }
        }

        return $posts;     
    }

    public static function countSearch($terme) {
        $query = self::execute("SELECT * FROM post WHERE ( Title like :Title or Body like :Body) order by Timestamp DESC", array("Title" => "%".$terme."%", "Body" => "%".$terme."%"));
        
        return $query->rowCount();
    }

    public static function newest($limit, $offset) {

        $query = self::execute("SELECT * FROM post where ParentId is null ORDER BY Timestamp DESC LIMIT $limit OFFSET $offset" , array());
        $data = $query->fetchAll();
        $posts = [];
        foreach($data as $row) {
            $posts[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId'], $row['PostId']);
        }
        return $posts;
    }

    public static function unanswered($limit, $offset) {

        $query = self::execute("SELECT * FROM post where AcceptedAnswerId is null AND ParentId is null ORDER BY Timestamp DESC LIMIT $limit OFFSET $offset", array());
        $data = $query->fetchAll();
        $posts = [];
        foreach($data as $row) {
            $posts[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId'], $row['PostId']);
        }
        return $posts;
    }

    public static function countUnanswered() {
        $query = self::execute("SELECT * FROM post where AcceptedAnswerId is null AND ParentId is null ", array());
        return $query->rowCount();
    }

    public static function active($limit, $offset) {
 
        $query = self::execute("SELECT question.PostId, question.AuthorId, question.Title, question.Body, question.ParentId, question.Timestamp, question.AcceptedAnswerId 
        from post as question, 
             (select post_updates.postId, max(post_updates.timestamp) as timestamp from (
                    select q.postId as postId, q.timestamp from post q where q.parentId is null
                    UNION
                    select a.parentId as postId, a.timestamp from post a where a.parentId is not null
                    UNION
                    select c.postId as postId, c.timestamp from comment c 
                    UNION 
                    select a.parentId as postId, c.timestamp 
                    from post a, comment c 
                    WHERE c.postId = a.postId and a.parentId is not null
                    ) as post_updates
                    group by post_updates.postId) as last_post_update
                    where question.postId = last_post_update.postId and question.parentId is null
                    order by last_post_update.timestamp DESC LIMIT $limit OFFSET $offset", array());
        $data = $query->fetchAll();
        $posts = [];
        foreach($data as $row) {

            $posts[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId'], $row['PostId']);

        }
        return $posts;
        
    }

    public static function vote($limit, $offset) {

        $query = self::execute("SELECT post.*, max_score
                                FROM post, (
                                    SELECT parentid, max(score) max_score
                                    FROM (
                                        SELECT post.postid, ifnull(post.parentid, post.postid) parentid, ifnull(sum(vote.updown), 0) score
                                        FROM post LEFT JOIN vote ON vote.postid = post.postid
                                        GROUP BY post.postid
                                    ) AS tbl1
                                    GROUP by parentid
                                ) AS q1
                                WHERE post.postid = q1.parentid
                                ORDER BY q1.max_score DESC, timestamp DESC LIMIT $limit OFFSET $offset", array());
        $data = $query->fetchAll();
        $post = [];
        foreach ($data as $row) {
            $post[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId'], $row['PostId']);
        }

        return $post;
    }

    /*
     *Cette méthode permet d'obtenir un post à partir d'un postId
     * 
     * @Return Post
     * 
     * @param $PostId
     * 
     */
    public static function getPostByPostId($PostId) {
        $query = self::execute("SELECT * FROM post where PostId = :PostId ", array("PostId" => $PostId ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Post($data['AuthorId'], $data['Title'], $data['Body'], $data['Timestamp'], $data['AcceptedAnswerId'], $data['ParentId'], $data['PostId']);
        }
    }

    /*
     *Cette méthode permet d'obtenir un post à partir d'un DE LA DERNIERE insertion 
     * 
     * @Return Post
     * 
     * @param $lastInsertIndex
     * 
     */
    public function getPostByLastInsert() {
        $query = self::execute("SELECT * FROM post where PostId = :PostId ", array("PostId" => $this->lastInsertId() ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Post($data['AuthorId'], $data['Title'], $data['Body'], $data['Timestamp'], $data['AcceptedAnswerId'], $data['ParentId'], $data['PostId']);
        }
    }

    public static function getPostByTagId($TagId, $limit, $offset) {
        $query = self::execute("SELECT * FROM posttag WHERE TagId = :TagId LIMIT $limit OFFSET $offset", array("TagId" => $TagId));
        $data = $query->fetchAll();
        $posts = [];
        foreach($data as $row) {
            $postTag = new PostTag($row['PostId'], $row['TagId']);
            $post = Post::getPostByPostId($postTag->PostId);
            $posts[] = $post;
        }

        return $posts;
    }

    public static function countPostByTag($TagId) {
        $query = self::execute("SELECT * FROM posttag WHERE TagId = :TagId", array("TagId" => $TagId));

        return $query->rowCount();
    }
    public  function getTagByPostId() {
        $query =  self::execute('SELECT TagName, tag.TagId FROM postTag, tag WHERE PostId = :PostId and postTag.TagId = tag.TagId', array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        $tags = [];
        foreach($data as $row) {
            $tags[] = new Tag($row['TagName'], $row['TagId']);
        }

        return $tags;
    }

    public static function getPostTagByPostId($PostId) {
        $query =  self::execute('SELECT * FROM postTag WHERE PostId = :PostId', array("PostId" => $PostId));
        $data = $query->fetchAll();
        $postTags = [];
        foreach($data as $row) {
            $postTags[] = new PostTag($row['PostId'], $row['TagId']);
            
        }
        return $postTags;
    }

     /*
     *Cette méthode permet d'obtenir un tableau de Votes dans la db
     * 
     * @Return Vote []
     * 
     * @param $PostId
     * 
     */
    public function getVotes() {
        $query = self::execute('SELECT * FROM vote where PostId = :PostId', array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        $votes = [];
        foreach($data as $row) {
            $votes[] =  new Vote($row['UserId'], $row['PostId'], $row['UpDown']);  
        }
        return $votes;
    }

    public static function getNombrePost() {
        $query = self::execute('SELECT * FROM post WHERE ParentId is null', array());
        return $query->rowcount();
        
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

