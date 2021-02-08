<?php

require_once 'model/Post.php';
require_once 'model/User.php';
require_once 'model/Tag.php';
require_once 'model/PostTag.php';
require_once 'framework/View.php';
require_once "framework/Utils.php";
require_once 'framework/Controller.php';

class ControllerPost extends Controller {
    public function index() {
        $this->newest();
    }


    public function askQuestion() {
        $user = $this->get_user_or_false();
        $Timestamp = date("Y-m-d H:i:s");
        $AuthorId = $user->UserId;
        $errorTitle = "";
        $errorBody = "";
       $errorTag = "";
        $Title = "";
        $Body = "";
        $choix = [];
        $tags = Tag::getTags();
        $max_tags = Configuration::get('max_tags');

        if(isset($_POST['Title']) && isset($_POST['Body'])) {           
            $Title = $_POST['Title'];
            $Body = $_POST['Body'];
            if(isset($_POST['choix'])) {
                $choix = $_POST['choix'];
            }
            $errorTag = count($choix) > $max_tags;
            $errors = Post::validate($Title, $Body);
            $errorTitle = $errors['Title'];
            $errorBody = $errors['Body'];

            if($errorTitle == "" && $errorBody == "" && $errorTag == "") {
                $post = new Post($AuthorId, $Title, $Body, $Timestamp, $AcceptedAnswerId = null, $ParentId = null);
                $post->update();
                $postId = $post->getPostByLastInsert()->PostId;
                
                foreach($choix as $TagId) {
                    $postTag = new PostTag($postId, $TagId);
                    $postTag->insert();
                }
                $this->redirect("post", 'index');
            }
        }
        (new View('askQuestion'))->show(array("Title"=>$Title, "Body"=>$Body, "tags"=>$tags, "user" => $user, "errorTitle" => $errorTitle, "errorBody" => $errorBody, "errorTag"=>$errorTag));
    }

    public function editPost() {
        $user = $this->get_user_or_false();

        $Title = "";
        $Body = "";
        $Timestamp = date("Y-m-d H:i:s");
        $tagIds = [];
        $errorTitle = "";
        $errorBody = "";
        $choix = [];
        $errorTag = "";
        $tags = Tag::getTags();
        $max_tags = Configuration::get('max_tags');

        if(isset($_GET['param1'])) {
            $postid = $_GET['param1'];
            $post = Post::getPostByPostId($postid);
            $tagPost = $post->getTagByPostId();

            foreach($tagPost as $postTag) {
                $tagIds[] = $postTag->TagId;
            }
            $Title = $post->Title;
            $Body = $post->Body;
            $AcceptedAnswerId = $post->AcceptedAnswerId;
            $ParentId = $post->ParentId;

            if($ParentId == null){
                $questionId = $post->PostId;

                if(isset($_POST['Title']) && isset($_POST['Body'])) {
                    foreach($tagPost as $tag) {
                        $postTag = PostTag::getPostTagByPostIdAndTagId($postid, $tag->TagId);
                        $postTag->delete();
                    }
                    $Body = $_POST['Body'];
                    $Title = $_POST['Title'];                
                    $post->Title = $Title;
                    $post->Body = $Body;
                    $post->Timestamp =$Timestamp; 
                    if(isset($_POST['choix'])) {
                        $choix = $_POST['choix'];
                    }
                    $errorTag = count($choix) > $max_tags;
                    $errors = Post::validate($Title, $Body);
                    $errorTitle = $errors['Title'];
                    $errorBody = $errors['Body'];

                    if($errorTitle == "" && $errorBody == "" && $errorTag == "") {
                        $post->update();

                        foreach($choix as $TagId) {
                            $posttag = new PostTag($postid, $TagId);
                            $posttag->insert();
                        }                        
                        $this->redirect("post", 'getPost', $questionId);
                    }
                }
            } else {
                $questionId = $post->ParentId;
                if(isset($_POST['Body'])) {                
                    $post->Body = $_POST['Body'];
                    $post->Timestamp =$Timestamp;      
                    $post->update();
                    $this->redirect("post", "getPost", $questionId);
                }
            }        
        }
        (new View('editPost'))->show(array("Title"=>$Title, "Body"=>$Body, "post"=>$post, "user" => $user, "tags"=>$tags, "tagIds" => $tagIds, "errorTitle" => $errorTitle, "errorBody" => $errorBody, "errorTag"=>$errorTag));        
    }

    public function reply() {
        $user = $this->get_user_or_false();

        $Timestamp = date("Y-m-d H:i:s");
        $AuthorId = $user->UserId;

        if(isset($_GET['param1']) && isset($_POST['Body'])) {
            $Body = $_POST['Body'];
            $ParentId = $_GET['param1'];;

            $post = new Post($AuthorId, "", $Body, $Timestamp, null, $ParentId);

            $post->update();

            $this->redirect("post", "getPost", $ParentId);
        }
    }

    public function accept() {
        $user =  $this->get_user_or_false();
        
        if(isset($_GET['param1'])) {
            $PostId = $_GET['param1'];
            $post = Post::getPostByPostId($PostId);
            $parentid = $post->ParentId;
            $parent = Post::getPostByPostId($post->ParentId);
            if($parent->AcceptedAnswerId == null || $parent->AcceptedAnswerId != $PostId) {
                $parent->AcceptedAnswerId = $PostId;
                $parent->update();
            } elseif($parent->AcceptedAnswerId == $PostId) {
                $parent->AcceptedAnswerId = null;
                var_dump($parent->AcceptedAnswerId);
                $parent->update();
            }
            $this->redirect("post", "getPost", $parentid);
        }
    }

    public function delete() {
        $user = $this->get_user_or_false();
        $postTags = [];

        if(isset($_GET['param1'])) { 

            $postId = $_GET['param1'];
            $post = Post::getPostByPostId($postId);
            var_dump($postId);
            if(isset($_POST['delete'])) {
                var_dump("ok");
                if($post->ParentId != null) {
                    $questionId = $post->ParentId;
                    var_dump($questionId);

                    if($post->getVotes()){
                        var_dump(($post->getVotes()));
                        foreach($post->getVotes() as $vote){
                            $vote->delete();
                        }
                    }
                    var_dump('avant suppression');
                    $post->delete();
                    var_dump('après suppression');

                    $this->redirect("post", "getPost", $questionId);
                } else {
                    if($post->getCountAnswers() == 0){
                        if($post->getVotes()){
                            foreach($post->getVotes() as $vote){
                                $vote->delete();
                            }
                        }
                        $postTags = Post::getPostTagByPostId($postId); 
                        if(count($postTags) != 0) {
                            foreach($postTags as $postTag) {
                                $postTag->delete();
                            }
                        }
                        $post->delete();
                    }
                    $this->redirect("post", "newest");
                }
            }
        }
        
        (new view('delete'))->show(array("post" => $post, "user" => $user));
    }

    public function deleteTagPost() {
        $user = $this->get_user_or_false();
        $TagId = "";
        $TagName = "";

        if(isset($_GET['param1']) && isset($_GET['param2'])) {
            $TagId = $_GET['param1'];
            $PostId = $_GET['param2'];
            $post = Post::getPostByPostId($PostId);
            $tag = Tag::getTagByTagId($TagId);
            $TagName = $tag->TagName;
            $posttag = PostTag::getPostTagByPostIdAndTagId($PostId, $TagId);

            if(isset($_POST['delete'])) {
                $posttag->delete();
                $this->redirect("post", "getPost", $PostId);             
            }
        }

        (new view('deletePostTag'))->show(array("posttag" => $posttag, "user" => $user, "post" => $post, "TagName" => $TagName, "tag" => $tag));
    }

    public function addPostTag() {
        $user = $this->get_user_or_false();
        $max_tags = configuration::get('max_tags');
        var_dump($max_tags);
        $errorTag = "";
        if(isset($_GET['param1']) && isset($_POST['tagId'])) {
            $TagId = $_POST['tagId'];
            $PostId = $_GET['param1'];
            $errorTag = PostTag::validate($max_tags, $PostId);
            if($errorTag == "") {
                $posttag = new PostTag($PostId, $TagId);
                $exist = PostTag::getPostTagByPostIdAndTagId($PostId, $TagId);
                if(!$exist) {
                    $posttag -> insert();
                } else {
                    $errorTag = "Ce tag est déjà associé à ce post";
                }         
            } 
            $this->redirect("post", "getPost", $PostId,0, $errorTag);
        }
    }

    public function getPostByTag() {
        $user = $this->get_user_or_false();
        $posts = [];
        $TagName = '';
        $TagId = null;
        $pageActive = "";
        $prev = "";
        $next = "";
        $nbPages = "";
        $url = "";
        $limit = configuration::get('limit');
        $offset = "";
        $page = 1;

        if(isset($_GET['param1'])) {
            $TagId = $_GET['param1'];
            $tag = Tag::getTagByTagId($TagId);

            $TagName = $tag->TagName;
            $offset = ($page - 1) * $limit;
            $posts = Post::getPostByTagId($TagId, $limit, $offset);
            $nbPost = Post::countPostByTag($TagId);
            $nbPages = ceil($nbPost/$limit);
            $prev = 0;
            $next = 1;
            if(isset($_GET['param2'])) {
                $page = intval($_GET['param1']);
                $prev = $page - 1;
                $next = $page + 1;
            } 
            $url = "post/getPostByTag";
    
            (new View("post"))->show(array("posts" => $posts, "page" => $page, "prev" => $prev, "next" => $next, "nbPages" => $nbPages, "url" => $url, "pageActive" => $pageActive, "user" =>$user, "TagName"=>$TagName, "TagId"=>$TagId));
        }
    }


    public function search() {
        $user = $this->get_user_or_false();
        $search = $_POST["terme"];
        $this->redirect("post", "searchPRG", Utils::url_safe_encode($search));
    }

    public function searchJson() {
        
        $search = $_POST['terme'];
        $this->redirect("post", "searchPRGJson", Utils::url_safe_encode($search));
    }

    public function searchPRG() {
        $pageActive = "searchResult";
        $user = $this->get_user_or_false();
        $TagId = null;
        $TagName = '';
        $posts = [];
        $page = "";
        $prev = "";
        $offset = "";
        $next = "";
        $url = "";
        $limit = Configuration::get("limit");

        if(isset($_GET['param1'])) {
            $search = Utils::url_safe_decode($_GET['param1']);
            $page = 1;
            $prev = 0;
            $next = 1;                      
        }

        $url = "post/search";
        $offset = ($page - 1) * $limit;
        $posts = Post::search($search, $limit, $offset);
        $nbPost = Post::countSearch($search);
        $nbPages = ceil($nbPost / $limit);

        (new View("post"))->show(array("posts" => $posts, "search" => $search, "pageActive" => $pageActive, "page" => $page, "prev" => $prev, "next" => $next, "url" => $url, "user" => $user, "nbPages" => $nbPages, "TagId" => $TagId, "TagName"=>$TagName, "limit" => $limit, "offset" => $offset));
    }

    public function searchPRGJson() {
        $pageActive = "searchResult";

        $user = $this->get_user_or_false();
        $limit = Configuration::get("limit");

        $search = Utils::url_safe_decode($_GET['param1']);

        $page = 1;
        $prev = 0;
        $next = 1;
        
        if(isset($_GET['param2'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        }

        $url = "post/searchJson/".Utils::url_safe_encode($search);
        $offset = ($page - 1) * $limit;
        
        $posts = Post::search($search, $limit, $offset);

        foreach($posts as $post) {
            $post->tags = $post->getTagByPostId();
            $post->getUser = $post->getUser();
            $post->answers = $post->getCountAnswers();
        }
        
        $nbPost = Post::countSearch($search); 
        $nbPages = ceil($nbPost / $limit);

        $data = [];
        $data['user'] = $user;
        $data['prev'] = $prev;
        $data['next'] = $next;
        $data['url'] = $url;
        $data['nbPages'] = $nbPages;
        $data['posts'] = $posts;
        $data['pageActive'] = $pageActive;
        
        echo json_encode($data);
    }

    /*
     *Cette méthode permet de renvoyer les détails d'une question
     * 
     * @Return view_show
     * 
     * @Param GET[$questionId]
     */
    public function getPost() { 
        $user = $this->get_user_or_false();
        $openComment = null;
        $tags = [];
        $tagIds =[];
        $TagId = "";
        $TagName = "";
        $allTags = Tag::getTags();
        $errorTag = [];

        if(isset($_GET['param3'])) {
            $errorTag = $_GET['param3'];
        }
        

        if(isset($_GET['param2'])) {
            $openComment = $_GET['param2'];
        }
        if(isset($_GET['param1'])) {
            $questionId = $_GET['param1'];
            $post = Post::getPostByPostId($questionId);
            $tags = $post->getTagByPostId();
            foreach($tags as $tag) {
                $TagId = $tag->TagId;
                $tagIds[] = $TagId;
                $TagName = $tag->TagName;
            }  
        }
        (new view('show'))->show(array("post" => $post, "tagIds" => $tagIds, "TagId"=> $TagId, "TagName" => $TagName, "allTags" => $allTags, "user" => $user, "tags"=>$tags, "openComment"=>$openComment,"errorTag" => $errorTag ));
    }

    public function getComment(){
        $user = $this->get_user_or_false();

        $comments = Post::getComment();
        (new View("show"))->show(array("comments" => $comments, "user" =>$user));
        
    }
    

    public function newest() {
        $pageActive = "newest";
        $user = $this->get_user_or_false();
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit); 

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/newest";
        $offset = ($page - 1) * $limit;
        $posts = Post::newest($limit, $offset);

        (new View("post"))->show(array("posts" => $posts,"pageActive" => $pageActive, "page" => $page, "prev" => $prev, "user" => $user, "next" => $next, "url" => $url, "nbPages" => $nbPages, "TagId"=>$TagId, "TagName"=>$TagName, "limit" => $limit, "offset" => $offset));
    }

    

    public function unanswered() {
        $pageActive = "unanswered";
        $user = $this->get_user_or_false();
        $TagId = null;
        $TagName = '';

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }
        $limit = Configuration::get("limit");
        $offset = ($page - 1) * $limit;
        $posts = Post::unanswered($limit, $offset);
        $nbPost = Post::countUnanswered();
        $nbPages = ceil($nbPost / $limit);

        $url = "post/unanswered";
        
        (new View("post"))->show(array("posts" => $posts, "pageActive" => $pageActive, "page" => $page, "prev" => $prev, "user" => $user, "next" => $next, "url" => $url, "nbPages" => $nbPages, "TagId"=>$TagId, "TagName"=>$TagName, "limit" => $limit, "offset" => $offset));
    }

    public function active() {
        $pageActive = "active";
        $user = $this->get_user_or_false();
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit);

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/active";
        $offset = ($page - 1) * $limit;
        $posts = Post::active($limit, $offset);

        (new View("post"))->show(array("posts" => $posts, "pageActive" => $pageActive, "page" => $page, "prev" => $prev, "user" => $user, "next" => $next, "url" => $url, "nbPages" => $nbPages, "TagId"=>$TagId, "TagName"=>$TagName, "limit" => $limit, "offset" => $offset));
    }

    public function vote() {
        $pageActive = "vote";
        $user = $this->get_user_or_false();
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit);

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/Vote";
        $offset = ($page - 1) * $limit;
        $posts = Post::vote($limit, $offset);

        (new View("post"))->show(array("posts" => $posts, "pageActive" => $pageActive, "page" => $page, "prev" => $prev, "user" => $user, "next" => $next, "url" => $url, "nbPages" => $nbPages, "TagId"=>$TagId, "TagName"=>$TagName, "limit" => $limit, "offset" => $offset));
    }

    public function newestJson() {
        $user = $this->get_user_or_false();
        $pageActive = "newest";
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit); 

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/newestJson";
        $offset = ($page - 1) * $limit;
        $posts = Post::newest($limit, $offset);
        foreach($posts as $post) {
            $post->tags = $post->getTagByPostId();
            $post->getUser = $post->getUser();
            $post->answers = $post->getCountAnswers();
        }

        $data = [];
        $data['pageActive'] = $pageActive;
        $data['user'] = $user;
        $data['posts'] = $posts; 
        $data['prev'] = $prev; 
        $data['next'] = $next; 
        $data['nbPages'] = $nbPages; 
        $data['url'] = $url; 
        $data['page'] = $page;
        $data['TagName'] = $TagName; 
        $data['TagId'] = $TagId; 

        echo json_encode($data);

    }
    public function unansweredJson() {
        $user = $this->get_user_or_false();
        $pageActive = "unanswered";
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit); 

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/unansweredJson";
        $offset = ($page - 1) * $limit;
        $posts = Post::unanswered($limit, $offset);
        foreach($posts as $post) {
            $post->tags = $post->getTagByPostId();
            $post->getUser = $post->getUser();
            $post->answers = $post->getCountAnswers();
        }

        $data = [];
        $data['user'] = $user;
        $data['pageActive'] = $pageActive;
        $data['posts'] = $posts; 
        $data['prev'] = $prev; 
        $data['next'] = $next; 
        $data['nbPages'] = $nbPages; 
        $data['url'] = $url; 
        $data['page'] = $page;
        $data['TagName'] = $TagName; 
        $data['TagId'] = $TagId; 

        echo json_encode($data);

    }
    public function activeJson() {
        $user = $this->get_user_or_false();
        $pageActive = "active";
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit); 

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/activeJson";
        $offset = ($page - 1) * $limit;
        $posts = Post::active($limit, $offset);
        foreach($posts as $post) {
            $post->tags = $post->getTagByPostId();
            $post->getUser = $post->getUser();
            $post->answers = $post->getCountAnswers();
        }

        $data = [];
        $data['user'] = $user;
        $data['pageActive'] = $pageActive;
        $data['posts'] = $posts; 
        $data['prev'] = $prev; 
        $data['next'] = $next; 
        $data['nbPages'] = $nbPages; 
        $data['url'] = $url; 
        $data['page'] = $page;
        $data['TagName'] = $TagName; 
        $data['TagId'] = $TagId; 

        echo json_encode($data);

    }
    public function voteJson() {
        $user = $this->get_user_or_false();
        $pageActive = "vote";
        $TagId = null;
        $TagName = '';

        $nbPost = Post::getNombrePost();
        $limit = Configuration::get("limit");
        $nbPages = ceil($nbPost / $limit); 

        if(isset($_GET['param1'])) {
            $page = intval($_GET['param1']);
            $prev = $page - 1;
            $next = $page + 1;
        } else {
            $page = 1;
            $prev = 0;
            $next = 1;
        }

        $url = "post/voteJson";
        $offset = ($page - 1) * $limit;
        $posts = Post::vote($limit, $offset);
        foreach($posts as $post) {
            $post->tags = $post->getTagByPostId();
            $post->getUser = $post->getUser();
            $post->answers = $post->getCountAnswers();
        }

        $data = [];
        $data['user'] = $user;
        $data['pageActive'] = $pageActive;
        $data['posts'] = $posts; 
        $data['prev'] = $prev; 
        $data['next'] = $next; 
        $data['nbPages'] = $nbPages; 
        $data['url'] = $url; 
        $data['page'] = $page;
        $data['TagName'] = $TagName; 
        $data['TagId'] = $TagId; 

        echo json_encode($data);

    }


    public function getPostJson() {
        $user = $this->get_user_or_false();
        $userId = $user->UserId;
        $postId = $_GET['param1'];
        $post = Post::getPostByPostId($postId);
        $post->score = $post->getScore();
        $post->acceptAnswer = $post->getAcceptedAnswer();
        $post->countAnswer = $post->getCountAnswers();
        $post->comments = $post->getComment();
        $post->postUser = $post->getUser();
        $post->Timestamp = $post->time();
        $post->tags = $post->getTagByPostId();
        $post->tagsPost = $post->getTagByPostId();
        $post->allTags = Tag::getTags();
        $post->countComment = $post->countComment();
        foreach($post->comments as $comment) {
            $comment->User = $comment->getUser();
            $comment->Timestamp = $comment->time();
        }
        
        foreach($post->tagsPost as $postTag) {
            $post->tagIds[] = $postTag->TagId;

        }

        $post->answers = $post->getAnswers();
        foreach($post->answers as $answer) {
            $answer->score = $answer->getScore();
            $answer->acceptAnswer = $answer->getAcceptedAnswer();
            $answer->countAnswer = $answer->getCountAnswers();
            $answer->comments = $answer->getComment();
            $answer->postUser = $answer->getUser();
            $answer->Timestamp = $answer->time();
            $answer->countComment = $answer->countComment();
            foreach($answer->comments as $comment) {
                $comment->User = $comment->getUser();
            };
        }

        $data = [];
        $data['user'] = $user;
        $data['post'] = $post;
        $data['userId'] = $post;
        
        echo json_encode($data);
    }


}



