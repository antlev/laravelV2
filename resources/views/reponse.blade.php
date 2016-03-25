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
                    </div>
                    <button class="btn btn-success pull-right" id="finished"><i class="fa fa-next"></i>Terminé</button>
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
                var general = 1;
                for(var i=0;i<obj.length;i++) {
                    general++;
                    var idd = obj[i].id;
                    $('#lastquizz').before('' +
                            '<div class="col-lg-12" id="quizz_'+obj[i].id+'" style="font-weight:bold"> Question ' +  obj[i].id + ' : ' + obj[i].nom + '? &nbsp;' +
                            '<button class="btn btn-default addQuestion_'+obj[i].id+'" id="addQuestion_'+obj[i].id+'" onclick="addQ('+idd+')">' +
                            '<i style="color:green;cursor:pointer" class="fa fa-plus-circle" ></i>Ajouter une réponse</button>&nbsp;<br>' +
                            '</div>' +
                            '<div class="col-lg-12" id="quizzreponse_'+obj[i].id+'">'+
                            '<div class="col-lg-6" id="rep_'+idd+'" style="font-weight:normal">Réponse : ' +
                            '<input type="text" style="width:60% !important" class="form-control" id="reponse_' + general + '"><br>' +
                            '</div>' +
                            '<div class="col-lg-6"><br>' +
                            '<input type="radio" id="'+general+'" name="groupe_'+obj[i].id+'">' +
                            '</div></div>');





                }
            }
        });



$('#finished').click(function() {
    var values = [];


$(':radio').each(function() {
    var BonneReponse = ""
    var id = $(this).attr("id");
    var idquestion = $(this).attr('name').replace('groupe_','');
    if($(this).prop('checked')) { BonneReponse = id;  }
    else { BonneReponse=""; }

    var item1 = {
        "data": { "Question": idquestion,"Reponse":id,"BonneReponse":BonneReponse,"PhraseReponse":$('#'+id).val()}
    };
    values.push(item1);


});
    jsonvar = JSON.stringify({ values });
    $.ajax({
        url: 'questionreponse',
        type: "post",
        data:{ 'QuestionReponse':jsonvar } ,
        success: function(data){
          if(data==1) { //window.location.href  = "{{url('welcome')}}";
          }
            else { alert('Une erreur est survenue, merci de contacter l\'administrateur'); }
        }
                    });
            });
    });
    function addQ(quizz) {
        var IdQuestion="";

        $(':radio').each(function() {
            IdQuestion++;
        })

        var id = event.target.id;
        $('#quizz_'+quizz).after(
                '<div class="col-lg-12" id="quizzreponse_'+quizz+'">'+
                '<div class="col-lg-6" style="font-weight:normal">' + 'Réponse : ' +
                '<input type="text" style="width:60% !important" class="form-control" id="reponse_' +  IdQuestion + '">' +
                '<br>' +
                '</div>' +
                '<div class="col-lg-6"><br>' +
                '<input type="radio" id="'+IdQuestion+'" name="groupe_' +quizz+ '">' +
                '</div></div>');


    }


</script>