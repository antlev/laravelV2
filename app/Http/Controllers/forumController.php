<?php
namespace App\Http\Controllers;

// use resources\views\index;
use DB;
use Input;

use Session;
use App\Http\Controllers\Controller;
use App\dataForum;
use Auth;
use Illuminate\Http\Request;

class forumController extends Controller{

	public function index(){

		$forum = forumController::getForum();
		$categories = forumController::getCategories();
		$nbTopic = array();
		$nbTopic = forumController::getNbTopic($categories);

		return view('forumIndexView',[ 'forum' =>  $forum],[ 'categories' =>  $categories],[ 'nbTopic' => $nbTopic]);
		// Renvoie la vue avec les categories ,les sous categories contenues dans les categories, et le nombre de topic par sous categorie

		// Appel d'une méthode pour les traitement avec la base de donnée
		// on créer un objet pret à stocker ces données
		// $dataForum = new dataForum;
		// var_dump($dataForum->getForum());
	}

	public function cat($cat){
 
		$titre = $cat ;
		$catName = forumController::getCatName($cat); // retourne le nom de la catégorie avec son id

		$topic = $this->getAllTopic($cat);

		$data = array(
			'topic' => $topic,
			'catName' => $catName,
			'cat' => $cat);

		return view('forumCatView',  $data);
	}

	public function topic($cat,$topic_id){
		$posts = forumController::getPosts($topic_id);
		$catName = forumController::getCatName($cat); // retourne le nom de la catégorie avec son id
		$topic = $this->getTopic($topic_id);

		$data = array(
			'posts' => $posts,
			'catName' => $catName,
			'topic' => $topic,
			'cat' => $cat);

		return view('forumTopicView', $data);
	}
	public function getCat($topic_id){
		//return DB::select('topic_cat')->where('topic_id',$topic_id);
		return DB::select("SELECT `topic_cat` FROM `forum_topic` WHERE `topic_id` = 1;");
	}
	public function getCatName($cat){ // retourne le nom de la catégorie avec son id
		return DB::table('forum_categorie')->where('cat_id',$cat)->value('cat_nom');
	}
	public function getPosts($topic){ /*Return a table containing all posts of the topic in parameter*/
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
	public function getCategories(){

		return DB::select("SELECT cat_id, cat_nom, cat_ordre, forum_id, cat_desc
			FROM forum_categorie
			ORDER BY cat_ordre ASC");
	}
	public function getAllTopic($cat){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_cat = '$cat'
			ORDER BY topic_id");
	}	
	public function getTopic($topic){

		return DB::select("SELECT *
			FROM forum_topic
			WHERE topic_id = '$topic'");
	}
	public function getNbTopic($sous_cat){
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

	}
	public function newTopic($cat){
		$catName = forumController::getCatName($cat);
		$data = array(
		'cat' => $cat,
		'catName' => $catName);
		return view('forumNewTopic',$data);
	}
	public function newMessage($cat,$topic){
		$data = array(
			'cat' => $cat,
			'topic' => $topic);
		return view('forumNewMessage',$data);
	}
    public function niketamere() { 

return 'ok';
    }
	public function postMessage(){
		//$catName = forumController::getCatName($cat);


		return 'ok'; 
		
        $data = Input::all(); 
		$createur_id = Auth::id();
		$message = $data['msg'];
		$date = date('Y-m-d H:i:s');
		$topic_id = $data['topic'];

		$posts = forumController::getPosts($topic_id);
		$topic = $this->getTopic($topic_id);

		$cat = forumController::getCat($topic_id); // retourne la catégorie du topic

		$catName = forumController::getCatName($cat[0]->topic_cat); // retourne le nom de la catégorie avec son id
		DB::table('forum_post')->insert([
			['post_createur' => $createur_id, 'post_texte' => $message, 'post_time' => $date , 'post_topic_id' => $topic[0]->topic_id ]
		]);
		//DB::select("INSERT INTO forum_post ('post_createur')
		//	VALUES('post_createur' = $createur_id,'post_texte' = $message['message'], 'post_time' = $date, 'post_topic_id' = $topic");
		

		$data = array(
			'posts' => $posts,
			'catName' => $catName,
			'topic' => $topic,
			'cat' => $cat[0]->topic_cat);

		echo "test";
		// forumController::topic($cat[0]->topic_cat,$topic[0]->topic_id);
		//header('location:/forum/'.$cat[0]->topic_cat.'/'.$topic[0]->topic_id."'");
		//return view('forumTopicView', $data);
	}


}

?>