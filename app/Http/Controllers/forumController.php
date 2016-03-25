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

	// Vérifie l'authentification de l'utilisateur
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Affiche la page d'index du forum
	public function index(){

		$forum = forumController::getForum();
		$categories = forumController::getCategories();
		$nbTopic = array();
		//$nbTopic = forumController::getNbTopic($categories);

		$topic = $this->getAllTopic();
		
		$data = array(
			'forum' =>  $forum,
			'categories' =>  $categories,
			'nbTopic' => $nbTopic,
			'topic' => $topic);


		return view('forumIndexView', $data);
		// Renvoie la vue avec les categories ,les sous categories contenues dans les categories, et le nombre de topic par sous categorie
	}

	// Affiche la page affichant les topics de la catégorie passée en paramètre
	public function cat($cat){


		$categories = forumController::getCategories();
		$catName = forumController::getCatName($cat); // retourne le nom de la catégorie avec son id
		$topic = $this->getTopicFromCatLimit($cat,1);
		$nbPost = array();
		$lastPostId = array();
		$lastPostCreator = array();
		foreach ($topic as $value) {
			array_push($nbPost, forumController::getNbPostByTopic($value->topic_id));
			array_push($lastPostId, forumController::__getLastPostId($value->topic_id));
			if ( forumController::__getLastPostCreator($value->topic_id) != null ) {
				array_push($lastPostCreator, forumController::__getLastPostCreator($value->topic_id));
			}
		}


		$data = array(
			'topic' => $topic,
			'categories' =>  $categories,
			'catName' => $catName,
			'cat' => $cat,
			'lastPostId' => $lastPostId,
			'lastPostCreator' => $lastPostCreator,
			'nbPost' => $nbPost);

		return view('forumCatView',  $data);
	}
	// Return the next page to print for the forumCatView
	public function nextCat($cat){

		$inputData = Input::all();
		$firstTopicToPrint = $inputData['lastTopicPrinted'] + 1;
		return  $this->getTopicFromCatLimit($cat,$firstTopicToPrint);
	}

	// Affiche la page affichant les posts de la catégorie et topic passés en paramètre
	public function topic($cat,$topic_id){
		$posts = forumController::getPosts($topic_id);
		$catName = forumController::getCatName($cat); // retourne le nom de la catégorie avec son id
		
		$topic = $this->getTopic($topic_id);

		$categories = forumController::getCategories(); // Permet de rediriger vers chaque catégories dans un menu déroulant

		$data = array(
			'posts' => $posts,
			'catName' => $catName,
			'topic' => $topic,
			'cat' => $cat,
			'categories' => $categories);

		return view('forumTopicView', $data);
	}
		// Affiche la page de création d'un topic
	public function newTopic($cat){
		$categories = forumController::getCategories();
		$catName = forumController::getCatName($cat);
		// TODO Check variable to be used
		$topic = forumController::getTopicFromCat($cat);
		$topics = $this->getAllTopic();

		$data = array(
		'categories' => $categories,
		'cat' => $cat,
		'catName' => $catName,
		'topics' => $topics,
		'topic' => $topic);
		return view('forumNewTopic',$data);
	}
	// Affiche la page d'ajout d'un message
	public function newMessage($cat,$topic_id){
		$categories = forumController::getCategories();
		$topics = $this->getAllTopic();

		$data = array(
			'categories' => $categories,
			'cat' => $cat,
			'topic_id' => $topic_id,
			'topics' => $topics);
		return view('forumNewMessage',$data);
	}

	// Requêtes à la base de donnée
	// Requêtes de selection :
	// Renvoie l'id de la catégorie correspondant au topic passé en paramètre
	public function getCat($topic_id){
		//return DB::select('topic_cat')->where('topic_id',$topic_id);
		return DB::select("SELECT `topic_cat` FROM `forum_topic` WHERE `topic_id` = 1;");
	}
	// Renvoie le nom de la catégorie en prenant son id en paramètre
	public function getCatName($cat){ 
		return DB::table('forum_categorie')->where('cat_id',$cat)->value('cat_nom');
	}
	//Return a table containing all posts of the topic in parameter
	public function getPosts($topic){ 
		//return DB::table('forum_post')->select('*')->orderBy('post_time','asc');

		return DB::select("SELECT *
			FROM forum_post
			WHERE post_topic_id = $topic
			ORDER BY post_time ASC");
	}

	public function getForum(){
		return DB::select("SELECT forum_id, forum_cat_id, forum_name, forum_desc
			FROM forum_forum
			ORDER BY forum_id ASC");
	}
	// Renvoie les catégories
	public function getCategories(){

		return DB::select("SELECT cat_id, cat_nom, cat_ordre, forum_id, cat_desc
			FROM forum_categorie
			ORDER BY cat_ordre ASC");
	}
	// Renvoie les topics appartenant à une catégorie entrée en paramètre
	public function getTopicFromCat($cat){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_cat = '$cat'
			ORDER BY topic_id");
	}	
	// Renvoie les topics appartenant à une catégorie entrée en paramètre
	public function getTopicFromCatLimit($cat,$firstTopicToPrint){
		return DB::table('forum_topic')->where('topic_cat', $cat)->orderBy('topic_id')->skip($firstTopicToPrint)->take(10)->get();
	}
	// Renvoie tous les topics
	public function getAllTopic(){

		return DB::select("SELECT *
			FROM forum_topic");
	}
	// Renvoie le topic en prenant en paramètre l'id du topic
	public function getTopic($topic){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_id = '$topic'");
	}
	private function __getCreateurPostById($post_id){
		return DB::table('forum_post')->where('post_id', '=', $post_id)->select('post_createur')->get();
	}
	private function __getPostMessageById($post_id){
		return DB::table('forum_post')->where('post_id', '=', $post_id)->select('post_texte')->get();
	}
	private function __getLastTopicId(){
		return DB::table('forum_topic')->select('topic_id')->orderBy('topic_id', 'desc')->first();
	}
	private function __getLastPostId($topic_id){
		return DB::table('forum_post')->select('topic_id')->where('post_topic_id', '=', $topic_id)->max('post_id');
	}
	private function __getLastPostCreator($topic_id){
		// TODO
		return DB::table('forum_post')->select('post_createur')->where('post_topic_id', '=', $topic_id)->orderBy('post_id', 'desc')->first();
	}

	public function getLastPostByCat(){

	}

	// Renvoie le nombre de topic
	public function getNbTopic($cat){

		return DB::table('forum_topic')->where('topic_cat', '=', $cat)->count();
	}

	public function getNbPostByTopic($topic_id){
		return DB::table('forum_post')->where('post_topic_id', '=', $topic_id)->count();
	}

	public function getNbPostByCat($cat){
		return DB::table('forum_post')->where('post_topic_id', '=', $topic_id)->count();
	}


	// Requêtes d'insertion :
	// Méthode appelée lors du post d'un message
	public function postMessage($cat,$topic){
		// Récupération des informations à sauvegarder en base
		//$catName = forumController::getCatName($cat);
        $inputData = Input::all(); 
		$createurId = Auth::id();
		$message = $inputData['msg'];
		$date = date('Y-m-d H:i:s');
		
		// Requete SQL qui permet d'insérer les données dans la base
		DB::table('forum_post')->insert([
			['post_createur' => $createurId, 'post_texte' => $message, 'post_time' => $date , 'post_topic_id' => $topic ]
		]);
	}
	public function supMessage($cat,$topic){
		        
	    $inputData = Input::all(); 
	    $post_id = $inputData['post_id'];

	    DB::table('forum_post')->where('post_id', '=', $post_id)->delete();
	}	
	public function editMessageView($cat,$topic_id,$post_id){
		// Vérifier que l'utilisateur a bien les droits sur le message
		$editeurId = Auth::id();
		if( Auth::isAdmin() ){
			$messageToEdit = forumController::__getPostMessageById($post_id)[0]->post_texte;

			$data = array(
				'post_id' => $post_id,
				'cat' => $cat,
				'topic_id' => $topic_id,
				'messageToEdit' => $messageToEdit);

			return view('forumEditMessage',$data);

		} else if( $editeurId == forumController::__getCreateurPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			dd('it s ok go on');
		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}

	public function editMessage($cat,$topic_id,$post_id){
		// Vérifier que l'utilisateur a bien les droits sur le message
		$editeurId = Auth::id();
		$inputData = Input::all(); 
		$messageRemplacant = $inputData['msgToSend'];

		if( Auth::isAdmin() ){
			DB::table('forum_post')->where('post_id', '=', $post_id)->update(['post_texte' => $messageRemplacant]);
		} else if( $editeurId == forumController::__getCreateurPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			dd('it s ok go on');
		} else {
			dd("sorry you don't have permission to edit this message");
		}
	}


	// Méthode appelée lors du post d'un nouveau topic
	public function createTopic($cat){

		$inputData = Input::all(); 
		$createurId = Auth::id();
		$messageTopic = $inputData['msgTopic'];
		$titreTopic = $inputData['titleTopic'];
		var_dump($messageTopic);
		var_dump($titreTopic);
		var_dump($createurId);
		var_dump($cat);

		// Creation du topic
		DB::table('forum_topic')->insert(
			['topic_titre' => $titreTopic, 'topic_createur' => $createurId, 'topic_time' => date('Y-m-d H:i:s'), 'topic_cat' => $cat]
		);
		$post_topic_id = forumController::__getLastTopicId();
		// Insertion du message
		DB::table('forum_post')->insert(
			['post_createur' => $createurId, 'post_texte' => $messageTopic, 'post_topic_id' => $post_topic_id->topic_id, 'post_time' => date('Y-m-d H:i:s')]
		);

	}

	public function test(){
		DB::table('forum_topic')->insert(
			['topic_titre' => "titre", 'topic_createur' => 2, 'topic_time' => date('Y-m-d H:i:s'), 'topic_cat' => 1]
		);
		dd('stop test');
	}
}
?>