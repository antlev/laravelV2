@extends('layouts.app')

@section('content')
        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Création des réponses </div>
                <br>
                <div class="panel-body">
                 
                                    <div id="lastquizz"></div>
                    <!-- <pre>{{ var_dump(Auth::user())}} </pre> -->
                </div>
                <button class="btn btn-success pull-right"><i class="fa fa-next"></i>Suivant</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>

<script>
$( document ).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$.ajax({
      url: 'question',
      type: "post",
      success: function(data){
         obj = $.parseJSON(data);
         for(var i=0;i<obj.length;i++) { 
      $('#lastquizz').before('<div class="col-lg-12" aid="quizz_'+i+'" style="font-weight:bold"> Question ' +  obj[i].id + ' : ' + obj[i].nom + '? &nbsp;<i style="color:green;cursor:pointer" class="fa fa-plus-circle" id="#addQuestion"></i>&nbsp;Ajouter une réponse<br> <div class="col-lg-6" style="font-weight:normal">Réponse: <input type="text" style="width:60% !important" class="form-control" id="reponse_'+obj[i]+'"><br></div><div class="col-lg-6"><br><input type="checkbox"></div></div>');
       
          }
       }
    }); 




$('#addQuestion').click(function() {
var previous = $('#lastquizz').prev().attr('id').replace('quizz_','');
var addone = parseInt(previous) + 1;
var i = 0;
//$('#lastquizz').before('<br>Réponse '+addone+': <input type="text" style="width:60% !important" class="form-control" id="reponse_'+addone+'"><br></div><div class="col-lg-6"><br><button id="'+addone+'" class="BonneReponse btn btn-success">Bonne réponse</button></div></div>');

$('[id^="quizz_"]').each(function(){
i++;

$($(this)).after('<div class="col-lg-12" id="quizz_'+i+'" style="font-weight:bold"> <div class="col-lg-6" style="font-weight:normal">Réponse : <input type="text" style="width:60% !important" class="form-control" id="reponse_'+i+'"><br></div><div class="col-lg-6"><br><button id="'+addone+'" class="BonneReponse btn btn-success">Bonne réponse</button></div></div>');
});
    });


    });


</script>