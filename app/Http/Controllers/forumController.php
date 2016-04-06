<?php
namespace App\Http\Controllers;

use DB; // Allow to use DB request
use Input;
use Session;
use App\Http\Controllers\Controller;
use App\dataForum;
use Auth; // Allowto use directly Auth methods
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector; // Contains the methods used to redirect url

class forumController extends Controller{

	// Checks the authentification of the user, the user must be identified to access the forum
    public function __construct()
    {
        $this->middleware('auth');
    }

  /**
	Functions used to return the views
	All these functions get the necessary data and 
	return the view passing all the data in an array
  */
    // Return the forumIndexView with correct data
	public function index(){
		$forum = $this->__getForum();
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$nbTopic = array(); // Store the number of topic for each categorie
		$nbPost = array(); // Store the number of post for each categorie
		$lastPost = array(); // Store data about the last post
		$lastPostCreator = array(); // Store the name of the last post creator
		// GETTING ALL INFORMATION
		foreach ($categories as $cat) {
			array_push($nbTopic, $this->getNbTopicByCat($cat->cat_id));
			array_push($nbPost, $this->getNbPostByCat($cat->cat_id));
			// First we get the last post creator id then his name using Auth
			array_push($lastPostCreator, Auth::getNameById($this->__getLastPostCreatorIdByCat($cat->cat_id)->post_createur));
			array_push($lastPost, $this->__getLastPostByCat($cat->cat_id));
		}
		$topics = $this->__getAllTopics();
		// Puts information into an array to send everything to 'forumIndexView' 
		$data = array(
			'forum' =>  $forum,
			'categories' =>  $categories,
			'nbTopic' => $nbTopic,
			'nbPost' => $nbPost,
			'lastPost' => $lastPost,
			'lastPostCreator' => $lastPostCreator,
			'topics' => $topics);
		// Return the view passing $data as a parameter
		return view('forumIndexView', $data);
	}
	// Return the forumCatView with correct data
	public function cat($cat){
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$catName = $this->__getCatName($cat); // Get the categorie name using the id
		$topic = $this->__getTopicFromCatLimit($cat,0); // Return a fixed number of topics starting at the id which is the second parameter
		$topics = $this->__getAllTopics(); // Return all topics (for navigation)
		$lastTopicFromCat = $this->__getLastTopicIdByCat($cat)->topic_id; // Return last topic from categorie
		$nbPost = array(); // Store the number of post for each categorie
		$lastPostId = array();
		$lastPostCreator = array();

		foreach ($topic as $value) { // For each topics
			array_push($nbPost, $this->__getNbPostByTopic($value->topic_id)); // we get the number of posts
			array_push($lastPostId, $this->__getLastPostId($value->topic_id)); // we get the last post
			if ( $this->__getLastPostCreator($value->topic_id) != null ) {
				array_push($lastPostCreator, $this->__getLastPostCreator($value->topic_id)); // and the creator of the last post
			}
		}
		// Puts information into an array to send everything to 'forumIndexView' 
		$data = array(
			'topic' => $topic,
			'topics' => $topics,
			'lastTopicFromCat' => $lastTopicFromCat,
			'categories' =>  $categories,
			'catName' => $catName,
			'cat' => $cat, // passed as a parameter
			'lastPostId' => $lastPostId,
			'lastPostCreator' => $lastPostCreator,
			'nbPost' => $nbPost);
		return view('forumCatView',  $data);
	}
	// Return the forumTopicView with correct data
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
	// Return the forumNewTopicView with correct data
	public function newTopic($cat){
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$catName = $this->__getCatName($cat); // Get the parent categorie name
		// TODO Check variable to be used
		$topic = $this->__getTopicFromCat($cat); // Get all the topics in the categorie passed as a parameter
		$topics = $this->__getAllTopics(); // Get all topics
		$data = array(
		'categories' => $categories,
		'cat' => $cat,
		'catName' => $catName,
		'topics' => $topics,
		'topic' => $topic);
		return view('forumNewTopicView',$data);
	}
	// Return the newPostView with correct data
	public function newPost($cat,$topic_id){
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$topics = $this->__getAllTopics(); // Get all topics
		$data = array(
			'categories' => $categories,
			'cat' => $cat,
			'topic_id' => $topic_id,
			'topics' => $topics);
		return view('forumNewPostView',$data);
	}
	// Return the forumMyPostsView with correct data
	public function myPosts($auth){
		$posts = $this->__getPostByCreatorId($auth);
		$postCat = array();
		$nbPost = sizeof($posts);
		foreach ($posts as $post) {
			// For each post we get the categorie
			array_push($postCat, $this->__getCatByTopic($post->post_topic_id));
		}
		$data = array(
			'posts' => $posts,
			'nbPost' => $nbPost,
			'postCat' => $postCat);
		// Checks the authenticity of the user
		if( Auth::isAdmin() ){
			return view('forumMyPostsView', $data);
		} else if( Auth::id() == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return view('forumMyPostsView', $data);
		} else {
			var_dump("Permission denied");
			dd("Sorry you don't have permission to edit this message");
		}
	}
	public function myProfil($auth){
		// Checks the authenticity of the user
		if( Auth::isAdmin() ){
			$data = array();
			return view('forumMyProfilView', $data);
		} else if( Auth::id() == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return view('forumMyProfilView', $data);
		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}
	// Return the adminView
	public function adminView(){
		if( Auth::isAdmin() ){ // We checks that the user is an admin
			return view('forumAdminView');
		} else { // If not we return the index
			return index();				
		}
	}

/**
	Methods called by the jQuery Posts requests
*/
	// Return the next pages to print for the forumCatView
	public function getPostInfoById(){
			$topicData = Input::get('topicData');
			$creatorName = array();
			$nbPost = array();
			foreach($topicData as $creator => $creatorId){
				dd($creatorId);
				$creatorName[] = Auth::getNameById($creatorId);
				$nbPost[] = __getNbPostByTopic();
			}
			//TODO RETURN DATA ARRAY WITH NBPOST AND OTHER INFO
			$topicData = array(
				'creatorName' => $creatorName,
				'nbPost' => $nbPost);
		// json_encode while encode data to be usable in the jQuery request
     	return json_encode($creator);
	}
	public function nextCat($cat){
		$inputData = Input::all(); // Getting data from Post_Request
		$firstTopicToReturn = $inputData['lastTopicPrinted'];
		$nbPost = array();
		// __getTopicFromCatLimit while return a certain number of topic starting at firstTopicToReturn
		$topics = $this->__getTopicFromCatLimit($cat,$firstTopicToReturn);
		foreach ($topics as $topic) {
			$nbPost[] = $this->__getNbPostByTopic($topic->topic_id);
		}
		$data = array(
			'topics' => $topics,
			'nbPost' => $nbPost);
/*		dd($data);
*/		// json_encode while encode data to be usable in the jQuery request	
		return  json_encode($data);
	}

/**
	Methods called from the views
*/
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
	/**
	// INSERTION SQL REQUESTS
	// function called by 'routes' which save the post into the database 
	*/
	public function postMessage($cat,$topic){
		// Retreive data
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
	public function supPostByPseudo(){
 		$inputData = Input::all();
	    $pseudoToSup = $inputData['pseudoToSup'];

	   	if(Auth::getIdByPseudo($pseudoToSup)[0]->id == 1){
	   		return $this->__supPostById(Auth::getIdByPseudo($pseudoToSup)[0]->id);
	   	} else {
			return null;
	   	}	   	
	}
	public function supPostByPostId(){
 		$inputData = Input::all();
	    $postIdToSup = $inputData['postIdToSup'];

	   	return DB::table('forum_post')
	   		->where('post_id',$postIdToSup) 
	   		->delete();	
	}
	public function getPostById(){
 		$inputData = Input::all();
	    $idToPrint = $inputData['idToPrint'];

	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idToPrint)
	    	->get();
	}
	public function getPostByName(){
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
	public function getPostByPseudo(){
 		$inputData = Input::all();
	    $pseudoToPrint = $inputData['pseudoToPrint'];
	    $idCreator = Auth::getIdByPseudo($pseudoToPrint)[0]->id;

	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idCreator)
	    	->get();
	}
	public function getPostByPostId(){
 		$inputData = Input::all();
	    return $this->__getPost($inputData['postIdToPrint']);
	}
/*	TODO
	public function getPostByDate(){
 		$inputData = Input::all();
	    $pseudoToPrint = $inputData['pseudoToPrint'];
	    $idCreator = Auth::getIdByPseudo($pseudoToPrint)[0]->id;

	    return DB::table('forum_post')
	    	->where('post_createur', '=', $idCreator)
	    	->get();
	}
*/
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

/**
	DATABASE REQUESTS
	SELECTION SQL REQUESTS :

	Reminder : the prefix __function() means that function is private

*/
	// Return the category's name taking it's id as a parameter
	private function __getCatName($cat){ 
		return DB::table('forum_categorie')->where('cat_id',$cat)->value('cat_nom');
	}
	// Return a table containing all posts of the topic in parameter
	private function __getPosts($topic){ 
		return DB::table('forum_post')
			->where('post_topic_id', $topic)
			->orderBy('post_time')
			->get();
	}

	private function __getPost($postId){
		return DB::table('forum_post')
			->where('post_id', $postId)
			->get();
	}

	private function __getForum(){
		return DB::table('forum_forum')
			->orderBy('forum_id')
			->get();

	}
	// Return all categories
	private function __getAllCategories(){
		return DB::table('forum_categorie')
			->orderBy('cat_ordre')
			->get();
/*		return DB::select("SELECT cat_id, cat_nom, cat_ordre, forum_id, cat_desc
			FROM forum_categorie
			ORDER BY cat_ordre ASC");*/
	}
	// Return the topics which are in the category passed as a parameter
	private function __getTopicFromCat($cat){
		return DB::table('forum_topic')
			->where('topic_cat', $cat)
			->orderBy('topic_id')
			->get();
/*		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_cat = '$cat'
			ORDER BY topic_id");*/
	}	
	// Return the topics which are in the category passed as a parameter
	// This function start with topic firstTopicToReturn and return x TODO(define x) topics max
	private function __getTopicFromCatLimit($cat,$firstTopicToReturn){
		return DB::table('forum_topic')->where('topic_cat', $cat)->orderBy('topic_id')->skip($firstTopicToReturn)->take(2)->get();
	}
	// Return all topics
	private function __getAllTopics(){
		return DB::table('forum_topic')
			->get();
/*		return DB::select("SELECT *
			FROM forum_topic");*/
	}
	// Return the topic given a a parameter
	private function __getTopic($topicId){

			return DB::table('forum_topic')
				->where('topic_id', $topicId)
				->get();
/*		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_id = '$topicId'");*/
	}
	public function __getCatByTopic($topicId){

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
	// Return the last post using categorie id
	private function __getLastPostByCat($cat){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')
			->where('forum_topic.topic_cat', $cat)			
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the last post creator's id of a certain category
	private function __getLastPostCreatorIdByCat(){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')			
			->select('post_createur')
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the last post date of a certain category
	private function __getLastPostDateByCat(){
		return DB::table('forum_post')
			
			->select('post_time')
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
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')
			->where('post_createur', $id)
			->get();
	}
	public function __getNbPostByTopic($topic_id){
		return DB::table('forum_post')
			->where('post_topic_id', '=', $topic_id)
			->count();
	}

	// Function used to test some code
	public function test(){
		dd($this->__getCatByTopic(39));
	}
}
?>