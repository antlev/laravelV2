<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail;
use Session;
use Auth;

class alexController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInfos()
    {
        return view('formulaire');
    }

    public function postInfos(Request $request)
    {
        $user = Auth::user();
        $tabs = array('civilite', 'nom','prenom','telephone','mobile','datenaiss','sport','categorie','adresse','cp','ville','nationalite');
        foreach ($tabs as $tab) {
            if($tab == 'datenaiss'){
                $ex = explode("/", $request[$tab]);
                $user->$tab = $ex[2].'-'.$ex[1].'-'.$ex[0];
            }
            else{$user->$tab = $request[$tab];}

        }
        if($request['classement']){$user->classement = $request['classement'];}
        $id_role = DB::select('SELECT distinct id from roles where nom = "Joueurs"');
        DB::table('user_roles')->where('user_id', $user->id)->update(array('id_role' => $id_role[0]->id));
        $user->save();
        return view('welcome');
    }

    public function mail2($user_id, $id_planning, $action, $request){
        $from = DB::select("SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from users where id = '".$user_id."'");
        // dd($from);
        // try {mail($from[0]->email, 'Test mail', 'je suis un Test');}
        // catch (Exception $ex){
        //     return $ex;
        // }

        // return 'totot';

        // $roles = DB::select("SELECT distinct r.nom, r.id  from planning p join participants pa on pa.id_planning = p.id join roles r on r.id = pa.id_role where p.id = ".$id_planning);
        Mail::send('email', $from , function($mess){
            // $mess->from('laravel@m2l.com', 'laravel');
            // $mess->to($from['email'], $from['nom']);
            // foreach ($roles as $role) {
            //     $and = '';
            //     if($roles[$role]['nom'] == 'Joueurs'){
            //         $and = " AND sport = '".$request['sport']."' AND categorie = '".$request['categorie']."'";
            //     }
            //     $email = "SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from user_roles ur join users u on u.id = ur.user_id where ur.id_roles = '".$roles[$role]['id']."'".$and;
            //     foreach ($email as $key ) {
            //         $mess->to($email[$key]['email'], $email[$key]['nom']);
            //     }                
            // }

        });
    }

    // public function suivi_mot_passe(){
    //     $types = DB::table('users')->paginate(100);
    //     $name = $this->getField('users');
    //     $return = array(
    //         'types' => $types,
    //         'names' => $name,
    //         'table' => 'users'
    //     ); 
    //     return view('suivi_mot_passe', $return);
    // }
    // public function suivi_reserve(){
    //     $types = DB::table('lieux')->paginate(100);
    //     $name = $this->getField('lieux');
    //     $return = array(
    //         'types' => $types,
    //         'names' => $name,
    //         'table' => 'lieux'
    //     ); 
    //     return view('suivi_reserve', $return);
    // }

    public function getCalendar(){
        return view('fullcalendar');
    }
    public function getId($table, $name, $field){
        echo "SELECT id FROM ".$table." WHERE ".$field."=".$name;
        return DB::table($table)->where($field, $name)->value('id');
    }
    public function postCalendar(Request $request){
        if ($request['action'] == 'delete'){
            DB::table('participants')->where('id_planning', $request['id'])->delete();
            DB::table('planning')->where('id', $request['id'])->delete();
        }
        else{
            $nom = (!empty($request['categorie'])) ? $request['type'].' '.$request['categorie']." ".$request['sport']." ".$request['lieu'] : $request['type']." ".$request['sport']." ".$request['lieu'];
            $lieu_id = DB::select('SELECT distinct id from lieux where nom = "'.$request['lieu'].'"');
            if($request['action'] == 'insert'){
                DB::table('planning')->insert([
                    ['nom' => $nom, 'debut' => $request['debut'], 'fin' => $request['fin'],'sport'=> $request['sport'], 'type' => $request['type'], 'lieu_name' => $request['lieu'], 'lieu_id' => $lieu_id[0]->id, 'user_id' =>Auth::id(), 'description' => $request['description'],'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                ]);
                $id_planning = DB::getPdo()->lastInsertId();
                $id_role = $this->getId('roles', "Joueurs", 'nom');
                $ok = 0;
                foreach (Auth::getRoles() as $key => $values) {
                    if($values->id_role == $this->getId('roles','Entraineurs', 'nom')){$ok = 1;}
                }
                if($request['sport'] != 'aucun' && $ok == 1){
                    DB::table('participants')->insert([
                        [ 'id_planning' => $id_planning, 'id_role' =>  $id_role[0]->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                    ]);
                }
                // else{
                //     foreach ($request['contact'] as $key => $value) {
                //        $id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
                //         DB::table('participant')->insert([
                //             ['id' => $id, 'id_planning' => $id_planning, 'id_role' => $value, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                //         ]); 
                //     }
                // }
                // User::mail($user_id, $id_planning, 'creation', $request);
            }
            else{
                if($request['action'] == 'drop'){
                    DB::table('planning')->where('id', $request['id'])
                    ->update(array('debut'=>$request['debut'] , 'fin'=> $request['fin'],'updated_at' => date('Y-m-d H:i:s')));
                    $this->mail2(Auth::id(), $request['id'], 'modif', false);
                }
                else if ($request['action'] == 'resize'){
                    DB::table('planning')->where('id', $request['id'])
                    ->update(array('fin'=> $request['fin'],'updated_at' => date('Y-m-d H:i:s')));   
                    // User::mail($user_id, $request['id'], 'modif', $request);
                }
                else{
                    DB::table('planning')->where('id', $request['id'])
                    ->update(array('nom'=> $nom, 'debut'=> $request['debut'], 'fin' => $request['fin'],'sport'=> $request['sport'], 'type' => $request['type'], 'lieu_name' => $request['lieu'], 'lieu_id' => $lieu_id[0]->id, 'description' => $request['description'],'updated_at' => date('Y-m-d H:i:s')));
                    // User::mail($user_id, $request['id'], 'modif', $request);
                }
            }
        }
    }

    public function postIndex(){
        // var_dump($_GET);
        $name = "(";
        $value = "(";
        $update = array();
        //Parcourt du tableau pour la remise en forme avant la création
        foreach($_GET['create'] as $key => $val){
            if($_GET['table'] == 'user_roles'){
                $val['value'] = ($val['name'] == 'role') ? $this->getId('roles',$val['value'], 'nom') : $this->getId('users', $val['value'], 'name');
                $val['name'] = ($val['name'] == 'role') ? 'id_role' : 'user_id';
            }
            else if($_GET['table'] == 'participants'){
                $val['value'] = ($val['name'] != 'nom_planning') ? $this->getId('roles',$val['value'], 'nom') : $this->getId('planning', $val['value'], 'nom');
                $val['name'] = ($val['name'] != 'nom_planning') ? 'id_role' : 'id_planning';
            }
            
            if($val['name'] == 'datenaiss' && !empty($val['value'])){
                $ex = explode("/", $val['value']);
                $val['value'] = $ex[2].'-'.$ex[1].'-'.$ex[0];
            }


            if($_GET['action'] == 'update'){
                $update = array_merge(array($val['name'] => $val['value']), $update);
            }
            else{
                $name .= $val['name'].',';
                $value .= '"'.$val['value'].'"'.',';                
            }
        }
        if($_GET['action'] == 'update'){
            $update = array_merge(array('updated_at' => date('YmdHis')), $update);
            // var_dump($update);
            DB::table($_GET['table'])->where('id', $_GET['id'])->update($update);
        }
        else{
            $name .= "created_at, updated_at)" ;
            $value .= date('YmdHis').",".date('YmdHis').")";
            // var_dump($name);
            // var_dump($value);            
            DB::select("insert into {$_GET['table']} {$name} values {$value}");
        }

    }
    public function index($table){
        $types = DB::table($table)->paginate(100);
        if($table == 'user_roles'){
            foreach ($types as $key => $value) {
                $role = DB::table('roles')->where('id', $value->id_role)->value('nom');
                $user = DB::select('SELECT distinct nom, prenom , name from users where id = "'.$value->user_id.'"');
                // dd($user);
                // $roles = DB::select('SELECT nom as "role" , id_role FROM user_roles u JOIN roles r on r.id = u.id_role where user_id = "'.$value->id.'"');
                // dd($roles);
                // dd($role);
                $types[$key]->role = $role;
                $types[$key]->nom = $user[0]->nom;
                $types[$key]->name = $user[0]->name;
                $types[$key]->prenom = $user[0]->prenom;
                
            }
        }
        else if($table == 'participants'){
            foreach ($types as $key => $value) {
                $role = DB::table('roles')->where('id', $value->id_role)->value('nom');
                $planning = DB::table('planning')->where('id', $value->id_planning)->value('nom');
                $types[$key]->role = $role;
                $types[$key]->nom_planning = $planning;
            }
        }
        else if($table == 'users'){
            for ($i=0; $i < $types->total(); $i++) { 
                if(!empty($types[$i]->datenaiss)){
                    $ex = explode("-", $types[$i]->datenaiss);
                    $types[$i]->datenaiss = $ex[2].'/'.$ex[1].'/'.$ex[0];                
                    
                }
            }
        }
        $name = $this->getField($table);
        $return = array(
            'types' => $types,
            'names' => $name,
            'table' => $table
        ); 
        // dd($return);
        return View('view_list', $return);
    }

    public function getField($table){
        $retour = array(
            'users' => array('id'=> 'nok','nom'=> 'ok','prenom'=> 'ok','name'=> 'ok', 'email'=> 'ok','datenaiss'=> 'ok','sport'=> 'ok','categorie'=> 'ok','mobile'=> 'ok','adresse'=> 'ok','ville'=> 'ok','cp'=>'ok','admin'=> 'ok'),
            'roles' => array('id'=> 'nok', 'nom'=> 'ok','created_at'=> 'nok','updated_at'=> 'nok'),
            'lieux' => array('id'=> 'nok', 'nom'=> 'ok', 'sport'=> 'ok','adresse'=> 'ok', 'created_at'=> 'nok','updated_at'=> 'nok'),
            'planning' => array('id' => 'nok', 'nom' => 'ok', 'debut'=>"ok",'fin' => 'ok','type' => 'ok','sport' => 'ok','lieu_name'=> 'ok','description'=> 'ok','created_at'=> 'nok','updated_at'=> 'nok'),
            'user_roles' => array('id'=> 'nok', 'id_role'=> 'nok', 'user_id'=> 'nok', 'role'=> 'ok','name'=> 'nok','nom'=> 'nok', 'prenom'=> 'nok', 'created_at'=> 'nok', 'updated_at'=> 'nok'),
            'participants' => array('id'=> 'nok','id_role'=> 'nok', 'id_planning'=> 'nok','role'=> 'ok','nom_planning'=> 'nok','created_at'=> 'nok','updated_at'=> 'nok'),
        );
        return $retour[$table];
    }

    public function autocomplete(){
        // dd($_GET);
        if($_GET['action'] == 'autocomplete'){
            if($_GET['table'] == 'users'){
                $lieux = DB::select('SELECT distinct name as "nom" from users');
            }
            else if ($_GET['table'] == 'participants'){
                $lieux = DB::select('SELECT distinct nom from planning');   
            }
            else{$lieux = DB::select('SELECT distinct '.$_GET["nom"].' from '.$_GET["table"].' where '.$_GET["name"].' ="'.$_GET['type'].'"');}
        }
        else{
            $lieux = DB::select('SELECT lieu_name as "lieu" count(*) from planning where debut between "'.$_GET['debut']
                .'" and "'.$_GET['fin'].'" OR fin between "'.$_GET['debut'].'" and "'.$_GET['fin'].'" GROUP BY lieu_id');
        }
        return json_encode($lieux);            
    }

    public function add_event(){
        $user = Auth::user();
        $or = "";
        foreach (Auth::getRoles() as $key => $values) {
            if($values->id_role == '4'){
                $id  = DB::select('SELECT p.id from planning p join participants pa on pa.id_planning = p.id where pa.id_role = 
                    (SELECT distinct id from roles where nom = "Joueurs") and  sport ="'.$user->sport.
                '" AND p.nom like "%'.$user->categorie.'%" AND user_id <>"'.$user->id.'"');
                $ids = "";
                foreach ($id as $key => $value) {
                    $ids .= $value->id.' ,';
                }
                $ids = substr($ids, 0, -1);
                if(!empty($ids) && strlen($ids) > 1)     {$or = "OR id in ('".$ids."') ";}
                else{$or = "";}
            }
        }
        $event = DB::select('SELECT id, nom, debut, fin, lieu_name as "lieu", type, description, sport, 
            CASE 
                WHEN user_id = '.$user->id.' then 1 
                WHEN user_id <> '.$user->id.' then 0
            END as "modif"
         from planning where user_id = "'.$user->id.'"'.$or);
        return json_encode($event);
    }

    public function update(){
        // dd($_GET);
        if($_GET['name'] == 'datenaiss' && !empty($_GET['value'])){
            $ex = explode("/", $_GET['value']);
            $_GET['value'] = $ex[2].'-'.$ex[1].'-'.$ex[0];
        }
        $value = ($_GET['name'] == 'id_role') ? $this->getId('roles',$_GET['value'],'nom') : $_GET['value'];
        DB::table($_GET['table'])->where('id', $_GET['id'])->update(array($_GET['name'] => $value, 'updated_at' => date('Y-m-d H:i:s')));
    }

    public function supp_view(){
        // var_dump($_GET);
        if($_GET['table'] == 'users'){
            DB::table('user_roles')->where('user_id', $_GET['data'][1])->delete();
            DB::table('planning')->where('user_id', $_GET['data'][1])->delete();
            DB::table('users')->where('id', $_GET['data'][1])->delete();
        }
       else if ($_GET['table'] == 'lieux'){
            DB::table('planning')->where('lieu_id', $_GET['data'][1])->update(array('lieu_id' => '', 'updated_at' => date('Y-m-d H:i:s')));
            DB::table('lieux')->where('id', $_GET['data'][1])->delete();
       }
       else if($_GET['table'] == 'roles'){
            DB::table('participants')->where('id_role',$_GET['data'][1])->delete();
            DB::table('user_roles')->where('id_role', $_GET['data'][1])->delete();
            DB::table('roles')->where('id', $_GET['data'][1])->delete();
       }
       else if($_GET['table'] == 'planning'){
            DB::table('participants')->where('id_planning',$_GET['data'][1])->delete();
            DB::table($_GET['table'])->where('id', $_GET['data'][1])->delete();
       }
       else{
            DB::table($_GET['table'])->where('id', $_GET['data'][1])->delete();
       }
    }
}