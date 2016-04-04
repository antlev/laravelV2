<?php
namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use App\Http\Controllers\Controller;
use App\dataForum;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector; // Contains the methods used to redirect url

class forumController extends Controller{

	// Checks the authentification of the user
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Return the forumIndexView with correct informations
	public function index(){

		$forum = $this->__getForum();
		$categories = $this->__getAllCategories();
		$nbTopic = array();
		$nbPost = array();
		$lastPost = array();
		$lastPostCreator = array();

		foreach ($categories as $cat) {
			array_push($nbTopic, $this->getNbTopicByCat($cat->cat_id));
			array_push($nbPost, $this->getNbPostByCat($cat->cat_id));
			//array_push($lastPost, $this->__getLastPostByCat($cat->cat_id));
			array_push($lastPostCreator, $this->__getLastPostCreator($cat->cat_id));
		}
		//$nbTopic = $this->__getNbTopic($categories);
		$topics = $this->__getAllTopics();

		
		$data = array(
			'forum' =>  $forum,
			'categories' =>  $categories,
			'nbTopic' => $nbTopic,
			'nbPost' => $nbPost,
			'lastPostCreator' => $lastPostCreator,
			'topics' => $topics);

		return view('forumIndexView', $data);
	}

	// Return the forumCatView with correct informations
	public function cat($cat){
		$categories = $this->__getAllCategories();
		$catName = $this->__getCatName($cat); // retourne le nom de la catégorie avec son id
		$topic = $this->__getTopicFromCatLimit($cat,0);
		$topics = $this->__getAllTopics();
		$lastTopicFromCat = $this->__getLastTopicIdByCat($cat)->topic_id;

		$nbPost = array();
		$lastPostId = array();
		$lastPostCreator = array();
		foreach ($topic as $value) {
			array_push($nbPost, $this->__getNbPostByTopic($value->topic_id));
			array_push($lastPostId, $this->__getLastPostId($value->topic_id));
			if ( $this->__getLastPostCreator($value->topic_id) != null ) {
				array_push($lastPostCreator, $this->__getLastPostCreator($value->topic_id));
			}
		}


		$data = array(
			'topic' => $topic,
			'topics' => $topics,
			'lastTopicFromCat' => $lastTopicFromCat,
			'categories' =>  $categories,
			'catName' => $catName,
			'cat' => $cat,
			'lastPostId' => $lastPostId,
			'lastPostCreator' => $lastPostCreator,
			'nbPost' => $nbPost);

		return view('forumCatView',  $data);
	}
	// Return the next pages to print for the forumCatView
	public function getNomById(){
			// $data = Input::get('idcreator');
			$data = $_POST['idcreator'];
			$creator = array();
			foreach($data as $creatorId){
				// array_push($creator, Auth::getNameById($creatorId));
				$creator[] = Auth::getNameById($creatorId);
			}
     	return json_encode($creator);
	}
	
	public function nextCat($cat){
		$inputData = Input::all();
		$firstTopicToPrint = $inputData['lastTopicPrinted'];
		return  json_encode($this->__getTopicFromCatLimit($cat,$firstTopicToPrint));
	}

	// Return the forumTopicView with correct informations
	public function topic($cat,$topic_id){
		$posts = $this->__getPosts($topic_id);
		$catName = $this->__getCatName($cat); // retourne le nom de la catégorie avec son id
		$topic = $this->__getTopic($topic_id);
		$categories = $this->__getAllCategories(); // Permet de rediriger vers chaque catégories dans un menu déroulant
		$topics = $this->__getAllTopics();

		$data = array(
			'posts' => $posts,
			'catName' => $catName,
			'topic' => $topic,
			'topics' => $topic,
			'cat' => $cat,
			'categories' => $categories);

		return view('forumTopicView', $data);
	}
	// Return the forumNewTopicView with correct informations
	public function newTopic($cat){
		$categories = $this->__getAllCategories();
		$catName = $this->__getCatName($cat);
		// TODO Check variable to be used
		$topic = $this->__getTopicFromCat($cat);
		$topics = $this->__getAllTopics();

		$data = array(
		'categories' => $categories,
		'cat' => $cat,
		'catName' => $catName,
		'topics' => $topics,
		'topic' => $topic);
		return view('forumNewTopicView',$data);
	}
	// Return the newPostView with correct informations
	public function newPost($cat,$topic_id){
		$categories = $this->__getAllCategories();
		$topics = $this->__getAllTopics();

		$data = array(
			'categories' => $categories,
			'cat' => $cat,
			'topic_id' => $topic_id,
			'topics' => $topics);
		return view('forumNewPostView',$data);
	}

	public function myPosts($auth){
		// TODO Checks user auth 
		$userId = Auth::id();


		if( Auth::isAdmin() ){
			$posts = $this->__getPostByCreatorId($auth);
			$postCat = array();
			$test = 0;
			foreach ($posts as $post) {
				$test++;
				array_push($postCat, $this->__getCatFromTopic($post->post_topic_id));
			}
			$data = array(
				'posts' => $posts,
				'postCat' => $postCat);
			return view('forumMyPostsView', $data);
		} else if( $userId == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post

		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}
	public function myProfil($auth){
		// TODO Checks user auth 
		$userId = Auth::id();


		if( Auth::isAdmin() ){
			$data = array();
			return view('forumMyProfilView', $data);
		} else if( $userId == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post

		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}

	public function adminView(){
		if( Auth::isAdmin() ){
			return view('forumAdminView');
		} else {
			return view('forumIndexView');				
		}
	}

	// DATABASE REQUESTS
	// SELECTION SQL REQUESTS :

	// Return the category's name taking it's id as a parameter
	private function __getCatName($cat){ 
		return DB::table('forum_categorie')->where('cat_id',$cat)->value('cat_nom');
	}
	// Return a table containing all posts of the topic in parameter
	private function __getPosts($topic){ 
		//return DB::table('forum_post')->select('*')->orderBy('post_time','asc');

		return DB::select("SELECT *
			FROM forum_post
			WHERE post_topic_id = $topic
			ORDER BY post_time ASC");
	}

	private function __getForum(){
		return DB::select("SELECT forum_id, forum_cat_id, forum_name, forum_desc
			FROM forum_forum
			ORDER BY forum_id ASC");
	}
	// Return all categories
	private function __getAllCategories(){

		return DB::select("SELECT cat_id, cat_nom, cat_ordre, forum_id, cat_desc
			FROM forum_categorie
			ORDER BY cat_ordre ASC");
	}
	// Return the topics which are in the category passed as a parameter
	private function __getTopicFromCat($cat){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_cat = '$cat'
			ORDER BY topic_id");
	}	
	// Return the topics which are in the category passed as a parameter
	// This function start with topic firstTopicToPrint and return x TODO(define x) topics max
	private function __getTopicFromCatLimit($cat,$firstTopicToPrint){
		return DB::table('forum_topic')->where('topic_cat', $cat)->orderBy('topic_id')->skip($firstTopicToPrint)->take(2)->get();
	}
	// Return all topics
	private function __getAllTopics(){

		return DB::select("SELECT *
			FROM forum_topic");
	}
	// Return the topic given a a parameter
	private function __getTopic($topicId){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_id = '$topicId'");
	}
	public function __getCatFromTopic($topicId){

		return DB::table('forum_topic')
			->where('topic_id', $topicId)
			->value('topic_cat');
	}	
	private function __getCreatorPostById($post_id){
		return DB::table('forum_post')
		->where('post_id', '=', $post_id)
		->select('post_createur')
		->get();
	}
	private function __getPostMessageById($post_id){
		return DB::table('forum_post')
		->where('post_id', '=', $post_id)
		->select('post_texte')
		->get();
	}
	private function __getLastTopicId(){
		return DB::table('forum_topic')
		->select('topic_id')
		->orderBy('topic_id', 'desc')
		->first();
	}

	private function __getLastTopicIdByCat($cat){
		return DB::table('forum_topic')
		->select('topic_id')
		->where('topic_cat', $cat)
		->orderBy('topic_id', 'desc')
		->first();
	}
	private function __getLastPostId($topicId){
		return DB::table('forum_post')
		->select('topic_id')
		->where('post_topic_id', '=', $topicId)
		->max('post_id');
	}
	private function __getLastPostCreator($topicId){
		// TODO
		return DB::table('forum_post')
			->select('post_createur')
			->where('post_topic_id', '=', $topicId)
			->orderBy('post_id', 'desc')
			->first();
	}

	// Return the last post creator's id of a certain category
	private function __getLastPostCreatorByCat(){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')			
			->select('post_createur')
			->orderBy('post_id', 'desc')
			->first();
	}

	// Return the number of topic which are in a category
	private function __getNbTopic($cat){

		return DB::table('forum_topic')
			->where('topic_cat', '=', $cat)
			->count();
	}

	private function __getPostByCreatorId($id){
		return DB::table('forum_post')
			->where('post_createur', $id)
			->get();
	}

	public function __getNbPostByTopic($topic_id){
		return DB::table('forum_post')
			->where('post_topic_id', '=', $topic_id)
			->count();
	}

	public function getNbPostByCat($cat){
		return DB::table('forum_post')
			->where('post_topic_id', '=', $cat)
			->count();
	}
	public function getNbTopicByCat($cat){
		return DB::table('forum_topic')
			->where('topic_cat', '=', $cat)
			->count();
	}

	// INSERTION SQL REQUESTS
	// function called by 'routes' which save the post into the database 
	public function postMessage($cat,$topic){
		// Retreive informations
        $inputData = Input::all(); 
		$createurId = Auth::id();
		$post = $inputData['msg'];
		
		// SQL request to insert data into the database
		DB::table('forum_post')->insert([
			['post_createur' => $createurId, 'post_texte' => $post, 'post_time' => date('Y-m-d H:i:s') , 'post_topic_id' => $topic ]
		]);
	}

	public function supPost($cat,$topic){
		        
	    $inputData = Input::all(); 
	    $postId = $inputData['postId'];

	    DB::table('forum_post')
	    	->where('post_id', '=', $postId)
	    	->delete();
	}

	public function supPostById(){
 		$inputData = Input::all();
	    $idToSup = $inputData['idToSup'];

	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idToSup)
	    	->delete();
	}
	private function __supPostById($idToSup){
	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idToSup)
	    	->delete();
	}


	public function supPostByName(){
 		$inputData = Input::all();
	    $nameToSup = $inputData['nameToSup'];
	   	$surnameToSup = $inputData['surnameToSup'];

	   	if(Auth::getIdByName($nameToSup,$surnameToSup) == null){
	   		return null;
	   	} else if(count(Auth::getIdByName($nameToSup,$surnameToSup)) == 1 ){
			if($this->__supPostById(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1){
				return 1;
			}
	   	} else {
	   		return -1;
	   	}	   	
	}

	public function printPostById(){
 		$inputData = Input::all();
	    $idToSup = $inputData['idToSup'];

	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idToSup)
	    	->get();
	}

	public function printPostByName(){
 		$inputData = Input::all();
	    $nameToSup = $inputData['nameToSup'];
	   	$surnameToSup = $inputData['surnameToSup'];
	   	dd(Auth::getIdByName($nameToSup,$surnameToSup));

	   	if(Auth::getIdByName($nameToSup,$surnameToSup) == null){
	   		return null;
	   	} else if(count(Auth::getIdByName($nameToSup,$surnameToSup)) == 1 ){
			if($this->__getPostByCreatorId(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1){
				return $this->__getPostByCreatorId(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1;
			}
	   	} else {
	   		return -1;
	   	}	   
	}

	// Function called in gt by 'routes' which return the 'forumEditPostView'
	public function editPostView($cat,$topicId,$postId){
		// Checks that user has the correct right pn the post
		$editorId = Auth::id();
		if( Auth::isAdmin() ){
			$postToEdit = $this->__getPostMessageById($postId)[0]->post_texte;
			$data = array(
				'postId' => $postId,
				'cat' => $cat,
				'topic_id' => $topicId,
				'postToEdit' => $postToEdit);

			return view('forumEditPostView',$data);

		} else if( $editorId == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			dd('it s ok go on');
		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}

	// Function called in post by 'routes' which modify a message into the database
	public function editPost($cat,$topicId,$postId){
		// Checks that user has the correct right pn the post
		$editeurId = Auth::id();
		$inputData = Input::all(); 
		$postToReplace = $inputData['msgToSend'];

		if( Auth::isAdmin() ){
			DB::table('forum_post')->where('post_id', '=', $postId)->update(['post_texte' => $postToReplace]);
		} else if( $editeurId == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			dd('it s ok go on');
		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}

	// Function called by 'routes' which insert the new topic into the database
	public function createTopic($cat){
		$inputData = Input::all(); 
		$creatorId = Auth::id();
		$messageTopic = $inputData['msgTopic'];
		$titleTopic = $inputData['titleTopic'];
		var_dump($messageTopic);
		var_dump($titleTopic);
		var_dump($creatorId);
		var_dump($cat);

		// Topic creation
		DB::table('forum_topic')->insert(
			['topic_titre' => $titleTopic, 'topic_createur' => $creatorId, 'topic_time' => date('Y-m-d H:i:s'), 'topic_cat' => $cat]
		);
		$postTopicId = $this->__getLastTopicId();
		// Post insertion 
		DB::table('forum_post')->insert(
			['post_createur' => $creatorId, 'post_texte' => $messageTopic, 'post_topic_id' => $postTopicId->topic_id, 'post_time' => date('Y-m-d H:i:s')]
		);

	}



	// Function used to test some code
	public function test(){
		dd($this->__getCatFromTopic(39));
	}
}
?>