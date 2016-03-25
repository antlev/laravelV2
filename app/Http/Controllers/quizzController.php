<?php

namespace App\Http\Controllers;
use Input;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;   
use Validator;
use Redirect;
use Response;
class quizzController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
  
  public function insertQuestions() {

$data = Input::get('insertQ');
$data = json_decode($data);
      //dd($data->values[0]->data->nom);

      foreach($data->values as $key => $questions) {
          DB::table('quizz')->insert([
              ['nom' => $questions->data->nom ]
          ]);
      }




  }
    public function getCategorie($id) {
        $categorie = DB::table('quizz_categorie')->where('id_categorie', $id)->first();
        return $categorie;


    }
    public function QuestionReponse() {

        $data = Input::get('QuestionReponse');
        $data = json_decode($data);
         $Question = array();

        foreach($data->values as $key => $questionreponse) {
            //dd($data->values[0]->data->Question);
          //  dd($questionreponse->data->BonneReponse);

            $idquestion = str_replace("group_","",$questionreponse->data->Question);

         array_push($Question, array('BonneReponse'=>$questionreponse->data->BonneReponse,'question'=>$questionreponse->data->Question));

            DB::table('quizz_reponse')->insert([
                ['reponse_reponse' => $questionreponse->data->Reponse,'idquestion_reponse'=>$idquestion]
            ]);


        }
        foreach($Question as $q) {
            $idquestion = str_replace("group_","",$q['question']);

            DB::table('quizz')->where('id',$idquestion)->update(array('idreponse' => $q['BonneReponse']));


}

            return '1';

    }
    public function getTheme($id) {
        $theme = DB::table('sports')->where('id_sports', $id)->first();
        return $theme;

    }
    public function checkAnswer() {
        $data = Input::get('answer');
dd($data);

    }
    public function getgame() {
        $quizz = DB::table('quizz')
            ->join('quizz_reponse', 'quizz_reponse.idquestion_reponse', '=', 'quizz.id')
            ->orderBy('quizz.id', 'desc')->get();
//dd($quizz);
        $quizzJson = array();
        foreach ($quizz as $quizzresult) {
            $id = $quizzresult->id;
            $nom = $quizzresult->nom;
            $reponse_id = $quizzresult->id_reponse;
            $reponse_nom = $quizzresult->nom_reponse;
            $bonnereponse = $quizzresult->correct_reponse;

            $categorie = $this->getCategorie($id);
            $theme = $this->getTheme($categorie->theme_categorie);

            array_push($quizzJson, array('id' => $id,'nom' => $nom,'reponseid'=>$reponse_id,'choix'=>$reponse_nom,'bonnereponse'=>$bonnereponse,'categorie'=>$categorie->nom_categorie,'theme'=>$theme->nom_sports));

        }

        return json_encode($quizzJson);


    }
    public function getQuestions() {
        $quizz = DB::table('quizz')->orderBy('id', 'asc')->get();
        $quizzJson = array();

        foreach ($quizz as $quizzresult) {
            $id = $quizzresult->id;
            $nom = $quizzresult->nom;
            array_push($quizzJson, array('id' => $id,'nom' => $nom));

//echo 'nickname: '.Auth::getNameById($shoutresult->iduser_shoutbox).' msg : '.$shoutresult->msg_shoutbox;
   }
return json_encode($quizzJson);
    }

public function quizzReponse()
    {
        return view('reponse');
    }
    public function gamepage() {

        return view('quizz_game');

    }
    public function index()
    {
        return view('quizz');
    }
}
