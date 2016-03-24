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

		$titre = $cat ;
		$catName = forumController::getCatName($cat); // retourne le nom de la catégorie avec son id
		$topic = $this->getTopicFromCat($cat);
		$categories = forumController::getCategories();

		$data = array(
			'topic' => $topic,
			'categories' =>  $categories,
			'catName' => $catName,
			'cat' => $cat);

		return view('forumCatView',  $data);
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
	// Renvoie le nombre de topic TODO
/*	public function getNbTopic($sous_cat){
		$nbTopic = array();
		for($i=0;$i<sizeof($sous_cat);$i++){
			$res = DB::select("SELECT count(topic_id)
				FROM forum_topic
				JOIN forum_forum using(forum_id)
				JOIN forum_categorie ON forum_cat_id = cat_id
				WHERE cat_id = $i
				GROUP BY topic_id");
			array_push($nbTopic, array($res));
		}
		return $nbTopic ;

	}*/

	// Méthode appelée lors du post d'un nouveau topic
	public function postTopic($cat){
		// Récupétration des informations
		$inputData = Input::all();
		$createur_id = Auth::id();
		$msgTopic = $inputData['msgTopic'];
		$titleTopic = $inputData['titleTopic'];
		$date = date('Y-m-d H:i:s');
		// Requête SQL d'insertion du topic dans la base
		DB::table('forum_topic')->insert(
			['topic_titre' => $titleTopic, 'topic_createur' => $createur_id,'topic_cat' => $cat,'topic_time' => $date]
		);
	}

	// Requêtes d'insertion :
	// Méthode appelée lors du post d'un message
	public function postMessage($cat,$topic){
		// Récupération des informations à sauvegarder en base
		//$catName = forumController::getCatName($cat);
        $inputData = Input::all(); 
		$createur_id = Auth::id();
		$message = $inputData['msg'];
		$date = date('Y-m-d H:i:s');
		
		// Requete SQL qui permet d'insérer les données dans la base
		DB::table('forum_post')->insert([
			['post_createur' => $createur_id, 'post_texte' => $message, 'post_time' => $date , 'post_topic_id' => $topic ]
		]);
	}
	public function supMessage($cat,$topic){
		        
	    $inputData = Input::all(); 
	    $post_id = $inputData['post_id'];

	    DB::table('forum_post')->where('post_id', '=', $post_id)->delete();
	}	
	public function editMessage($cat,$topic){
		
	}
}
?>