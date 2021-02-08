<?php

require_once "framework/Model.php";
require_once "comment.php";

class User extends Model {
    public $UserId;
    public $UserName;
    public $Password;
    public $Fullname;
    public $Email;
    public $Role;

    public function __construct($username, $password, $fullname, $email, $Role = "user", $UserId = "-1") {
        $this->UserId = $UserId;
        $this->UserName = $username;
        $this->Password = $password;
        $this->FullName = $fullname;
        $this->Email = $email;
        $this->Role = $Role;
    }

    public function isAdmin(){

        return $this->Role == "admin";
    }

    /*
     *Cette méthode permet de modifier ou d'inserer un utilisateur dans la db
     * 
     * @Return User
     */
    public function update() {
        if(self::getUserByUserId($this->UserId)) {
            self::execute("UPDATE user SET UserName=:username, Password =:Password, FullName=:FullName, Email = :Email, Role = :Role, WHERE UserId =:UserId", array("username"=>$this->UserName, "Password"=>$this->Password, "FullName"=>$this->FullName, "Email"=>$this->Email, "Role"=>$this->Role, "UserId" => $this->UserId));
        } else {
        self::execute("INSERT INTO user(UserName, Password, FullName, Email, Role) VALUES(:UserName, :Password, :FullName, :Email, :Role)", 
                      array("UserName"=>$this->UserName, "Password"=>$this->Password, "FullName"=>$this->FullName, "Email"=>$this->Email, "Role"=>$this->Role,));
        } 

        return $this;
    }

    /*
     *Cette méthode permet de supprimer un utilisateur dans la db
     * 
     * @Return void
     */
    public function delete() {
        self::execute("DELETE FROM user where UserId = :UserId", array("UserId" => $this->UserId));
    }

    /*
     *Cette méthode permet d'obtenir un utilisateur de la db
     * 
     * @Return User
     * 
     * @param $UserName
     */
    public static function getUserByUsername($UserName) {
        $query = self::execute("SELECT * FROM user where UserName = :UserName ", array("UserName" => $UserName));
        $data = $query->fetch();
        if($query->rowcount() == 0) {

            return false;
        } else {

            return new User($data['UserName'], $data['Password'], $data['FullName'], $data['Email'], $data['Role'], $data['UserId']);
        }
    }

    public static function getUserByEmail($Email) {
        $query = self::execute("SELECT * FROM user where Email = :Email ", array("Email" => $Email ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {

            return false;
        } else {

            return new User($data['UserName'], $data['Password'], $data['FullName'], $data['Email'], $data['Role'], $data['UserId']);
        }
    }

    public static function getUserByUserId($UserId) {
        $query = self::execute("SELECT * FROM user where UserId = :UserId ", array("UserId" => $UserId ));
        $data = $query->fetch();
        if($query->rowcount() == 0) {

            return false;
        } else {
            
            return new User($data['UserName'], $data['Password'], $data['FullName'], $data['Email'], $data['Role'], $data['UserId']);
        }
    }

    public static function validate_password($password){
        $errors = array();
        if (strlen($password) < 8 ) {
            $errors[] = "Le mot de passe doit avoir au moins 8 caractères";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/[0-9]/", $password) && preg_match("/['\";:,.\/?\\-]/", $password))) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre, une lettre majuscule et un caractère non alphanumérique.";
        }

        return $errors;
    }
    
    public static function validate_passwords($password, $password_confirm){
       /* $errors[] = User::validate_password($password);
        *$errors['Password'] = [];
        *if(count($errors) != 0) {
        *    $errors['Password'] == $errors;
        *}
        */
        $error = "";
        if ($password != $password_confirm) {
            $error = "Les mots de passe doivent être identiques.";
        }

        return $error;
    }
    
    public static function validate_unicity($UserName){
        $error = "";
        $User = self::getUserByUsername($UserName);
        if ($User) {
            $error = "Cet utilisateur existe déjà.";
        }

        return $error;
    }

    public static function validate_unicity_Email($Email){
        $error = "";
        $User = self::getUserByEmail($Email);
        if ($User) {
            $error = "Cet email est déjà utilisé.";
        }

        return $error;
    }

    public function validateUserName(){
        $errors = array();
        if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) > 0)) {
            $errors[] = "Le nom d'utilisateur est requis.";
        } if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) >= 3 )) {
            $errors[] = "Le nom de l'utilisateur est requis et doit avoir minimum 3 caractères.";
        }

        return $errors;
    }

    public function validateFullName(){
        $errors = array();
        if (!(isset($this->FullName) && is_string($this->FullName) && strlen($this->FullName) > 0)) {
            $errors[] = "Le prénom de l'utilisateur est requis.";
        } if (!(isset($this->FullName) && is_string($this->FullName) && strlen($this->FullName) >= 3 )) {
            $errors[] = "Le prénom de l'utilisateur est requis et doit avoir minimum 3 caractères.";
        }

        return $errors;
    }

    public function validateEmail(){
        $errors = array();
        if (!(isset($this->Email) && is_string($this->Email))) {
            $errors[] = "L'email est requis.";
        } if (!(filter_var($this->Email, FILTER_VALIDATE_EMAIL))) {
            $errors[] = "L'email de l'utilisateur n'est pas valide";
        }

        return $errors;
    }


    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        
        return $hash === Tools::my_hash($clear_password);
    }
    
    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($UserName, $password) {
        $errors['Password'] = "";
        $errors['UserName'] = "";
        $User = self::getUserByUsername($UserName);
        if ($User) {
            if (!self::check_password($password, $User->Password)) {
                $errors['Password'] = "mot de passe érroné.";
            }
        } else {
            $errors["UserName"] = "L'utilisateur avec le nom ".$UserName." n'existe pas. Veuillez vous inscrire.";
        }

        return $errors;
    }

    public static function allUsers($Timestamp) {

        $query = self::execute("SELECT * FROM user", array());
        $data = $query->fetchAll();
        $users =[];
        foreach($data as $row) {
            $user = new User($row['UserName'], $row['Password'], $row['FullName'], $row['Email'], $row['Role'], $row['UserId']);
            $user->nbActivity = $user->userActions($Timestamp);
            $users[] = $user;
        }

        return $users;
    }

    public function userActions($Timestamp) {
        $query = self::execute("SELECT c1 + c2 FROM ((select count(*) as c1 from post where AuthorId =:UserId and Timestamp > :Timestamp) as query1,
                                 (select count(*) as c2 from comment where UserId =:UserId and Timestamp > :Timestamp) as query2)",
                                 array("Timestamp" => $Timestamp, "UserId" => $this->UserId));
        $data = $query->fetch();
        
        return $data[0];
    }

    public function activityByUser($Timestamp) {
        $query = self::execute("SELECT * from post WHERE AuthorId = :UserId and Timestamp > :Timestamp", array("UserId" => $this->UserId, "Timestamp" => $Timestamp));
        $data = $query->fetchAll();
        $activity = [];
        foreach($data as $row) {
            if(!$row['ParentId']){
                $post = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
                $post->time = $post->time();
                $post->type = "create/update question";
                $activity[] = $post;
            } else {
                $response = Post::getPostByPostId($row['PostId']);
                $post = Post::getPostByPostId($row['ParentId']);
                $post->time = $response->time();
                $post->type = "create/update response";
                $activity[] = $post;
            }
        }

        $query = self::execute("SELECT * from comment WHERE UserId = :UserId and Timestamp > :Timestamp", array("UserId" => $this->UserId, "Timestamp" => $Timestamp));
        $data = $query->fetchAll();
        foreach($data as $row) {
            $question = Post::getPostByPostId($row['PostId']);
            $comment = Comment::getCommentByCommentId($row['CommentId']);
            if(!$question->ParentId){
                $post = $question;
                $post->time = $comment->time();
                $post->type = "create/update comment";
                $activity[] = $post;
            } else {
                $post = Post::getPostByPostId($question->ParentId);
                $post->time = $comment->time();
                $post->type = "create/update comment";
                $activity[] = $post;
            }
        }

        return $activity;
    }


}    

