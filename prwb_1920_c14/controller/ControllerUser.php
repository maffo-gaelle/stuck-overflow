<?php

require_once 'framework/view.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/User.php';

class ControllerUser extends Controller {
    public function index() {

        $this->redirect("post","newest");
    }

    public function login(){ 
        $UserName = "";
        $Password = "";
        $errorUserName = "";
        $errorPassword = "";

        if(isset($_POST['UserName']) && isset($_POST['Password'])) {
            $UserName = $_POST['UserName'];
            $Password = $_POST['Password'];
            
            $errors = User::validate_login($UserName, $Password);
            $errorUserName = $errors['UserName'];
            $errorPassword = $errors['Password'];
            if($errorPassword == "" && $errorUserName == "") {
                $this->log_user(User::getUserByUserName($UserName));
            }
        }

        (new View("login"))->show(array( "user"=>false, "UserName" => $UserName, "Password" => $Password, "errorUserName" => $errorUserName, "errorPassword" => $errorPassword));
    }

    public function signup() {
        $UserName = '';
        $FullName = '';
        $Email = '';
        $Password = '';
        $password_confirm = '';
        $errorUserNameUnique = "";
        $errorsUserName = [];
        $errorsFullName = []; 
        $errorsPassword = [];
        $errorPassword_confirm ="";
        $errorsEmail = [];
        $errorEmailUnique = "";

        if (isset($_POST['UserName']) && isset($_POST['FullName']) && isset($_POST['Email']) && isset($_POST['Password']) && isset($_POST['password_confirm'])) {
            $UserName = trim(($_POST['UserName']));
            $FullName =  trim(($_POST['FullName']));
            $Email = trim(($_POST['Email']));
            $Password = ($_POST['Password']);
            $password_confirm = ($_POST['password_confirm']);

            $user = new User($UserName, Tools::my_hash($Password), $FullName, $Email);
            $errorUserNameUnique = User::validate_unicity($UserName);
            $errorsUserName = $user->validateUserName();
            $errorsFullName = $user->validateFullName();
            $errorsEmail = $user->validateEmail();
            $errorEmailUnique = User::validate_unicity_Email($Email);
            $errorPassword_confirm = User::validate_passwords($Password, $password_confirm);
            $errorsPassword = User::validate_Password($Password);
            if(count($errorsUserName) == 0 && count($errorsFullName) == 0 && count($errorsEmail) == 0 && count($errorsPassword) == 0 && $errorPassword_confirm == "" && $errorEmailUnique == "" && $errorUserNameUnique == ""){
                $user->update();
                echo "ok";
                $this->log_user($user);
                $this->redirect("post", "index");
            }
        }
        (new View("signup"))->show(array("errorUserNameUnique"=>$errorUserNameUnique, "errorEmailUnique"=>$errorEmailUnique, "UserName"=>$UserName, "Password"=>$Password, "FullName"=>$FullName, "Email"=>$Email, "user"=>false, "errorsUserName"=>$errorsUserName, "errorsFullName"=>$errorsFullName, "errorsPassword"=>$errorsPassword, "errorPassword_confirm"=>$errorPassword_confirm, "errorsEmail"=>$errorsEmail ));
    }

    public function showGraph() {
        $user = $this->get_user_or_false();

        (new view("graph"))->show(array("user" => $user));

    }

    public function showGraphJson() {
        $data =  [];
        if(isset($_POST['period']) && isset($_POST['temp'])) {
            $period = $_POST['period'];
            $temp = $_POST['temp'];
            $timestamp = date("Y-m-d H:i:s", strtotime('- '.$period.' '.$temp));
            $users = User::allUsers($timestamp);
        }

        $data['limit'] = Configuration::get('limitUsers');
        $data['users'] = $users;

        echo json_encode($data);
    }

    public function usersActivity() {
        if(isset($_POST['UserName']) && isset($_POST['period']) && isset($_POST['temp'])) {
            $UserName = $_POST['UserName'];
            $period = $_POST['period'];
            $temp = $_POST['temp'];
            $timestamp = date("Y-m-d H:i:s", strtotime('- '.$period.' '.$temp));
            $user = User::getUserByUsername($UserName);
            $posts = $user->activityByUser($timestamp);
        }

        echo json_encode($posts);
    }

    public function username_available_service() {

        $res = "true";
        if(isset($_POST['UserName']) && $_POST['UserName'] != "") {
            $UserName = $_POST['UserName'];
            $user = User::getUserByUsername($UserName);

            if($user) {
                $res = "false";
            }
            echo $res;
        }
    }

    public function email_available_service() {
        $res = "true";
        if(isset($_POST['Email']) && $_POST['Email'] != "") {
            $Email = $_POST['Email'];
            $user = User::getUserByEmail($Email);
            if($user) {
                $res = "false";
            }

            echo $res;
        }
    }

    public function usernameLog_available_service() {

        $res = "true";
        if(isset($_POST['UserName']) && $_POST['UserName'] != "") {
            $UserName = $_POST['UserName'];
            $user = User::getUserByUsername($UserName);

            if(!$user) {
                $res = "false";
            }
            echo $res;
        }
    }

    public function password_available_service() {
        $res = "true";
        if(isset($_POST['Password']) && $_POST['Password'] != "" && isset($_POST['UserName']) && $_POST['UserName'] != "") {
            $Password = $_POST['Password'];
            $UserName = $_POST['UserName'];

            $user = User::getUserByUsername($UserName);

            if($user->Password != Tools::my_hash($Password)) {
                $res = "false";
            }
        }
        echo $res;
    }
}