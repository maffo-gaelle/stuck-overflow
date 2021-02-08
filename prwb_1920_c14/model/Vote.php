<?php

require_once "User.php";

class Vote extends Model {
    public $UserId;
    public $PostId;
    public $UpDown;


    public function __construct($UserId, $PostId, $UpDown) {
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        $this->UpDown = $UpDown;
    }

    /*
     *Cette méthode permet d'inserer un vote dans la db
     * 
     * @Return Vote
     */
    public function update() {
        self::execute("INSERT INTO vote(UserId, PostId, UpDown) VALUES(:UserId, :PostId, :UpDown)", 
        array("UserId"=>$this->UserId, "PostId"=>$this->PostId, "UpDown"=>$this->UpDown));

        return $this;
    }

    /*
     *Cette méthode permet de supprimer un vote dans la db
     * 
     * @Return void
     */
    public function delete() {
        self::execute("DELETE FROM vote where UserId = :UserId and PostId = :PostId", array("UserId"=> $this->UserId, "PostId"=> $this->PostId));
    }

    /*
     *Cette méthode permet d'obtenir un vote dans la db
     * 
     * @Return Vote
     * 
     * @params $UserId, $PostId
     */
    public static function getVoteByUserIdAndPostId($UserId, $PostId) {
        $query = self::execute("SELECT * FROM vote where UserId = :UserId and PostId = :PostId", array("UserId"=> $UserId, "PostId"=> $PostId)); 
        $data = $query->fetch();
        if($query->rowcount() == 0) {
            return false;
        } else {
            return new Vote($data['UserId'], $data['PostId'], $data['UpDown']);    
        }
    }

   
}
    

    