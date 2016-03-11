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
class HomeController extends Controller
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
public function getProfil() {

        return view('profil');
    

}

public function postImage() {
      
 $data = Input::all();
       
      $result =  DB::table('users')->where('id',Auth::id())->update(array('photo' => $data['img']));

 
       
        return $result;

}
public function changeUserInfo() {
        $data = Input::all(); 

Auth::changeUserInfo($data['column'],$data['info']);

}


public function getShouts() { 
 $shout = DB::table('shoutbox')->orderBy('id', 'asc')->get();
 $user = array();

foreach ($shout as $shoutresult) {
    $name = Auth::getNameById($shoutresult->iduser_shoutbox);
    $date = $shoutresult->date_shoutbox;
    $message = $shoutresult->msg_shoutbox;
    $sport = Auth::getSportbyId($shoutresult->iduser_shoutbox);
    $color = Auth::getColorById(Auth::getRoleById($shoutresult->iduser_shoutbox));
    array_push($user, array('name' => $name,'date' => $date,'msg' => $message,'sport'=>$sport,'color'=>$color));

//echo 'nickname: '.Auth::getNameById($shoutresult->iduser_shoutbox).' msg : '.$shoutresult->msg_shoutbox;
   }
    echo json_encode($user);

}
public function sbInsert(){

    $data = Input::all(); 
    DB::table('shoutbox')->insert([
            ['date_shoutbox' => $data['date'],'msg_shoutbox'=>$data['msg'],'iduser_shoutbox'=>$data['iduser']]
        ]);

}

    public function index()
    {
        return view('home');
    }
}
