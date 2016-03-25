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
                var general = 0;
                for(var i=0;i<obj.length;i++) {
                    var randomized = Math.floor((Math.random() * 10) + Math.random() * 100 + Math.random() * 10 / Math.random());
                    var idd = obj[i].id;
                    $('#lastquizz').before('' +
                            '<div class="col-lg-12" id="quizz_'+obj[i].id+'" style="font-weight:bold"> Question ' +  obj[i].id + ' : ' + obj[i].nom + '? &nbsp;' +
                            '<button class="btn btn-default addQuestion_'+obj[i].id+'" id="addQuestion_'+obj[i].id+'" onclick="addQ('+idd+','+randomized+')">' +
                            '<i style="color:green;cursor:pointer" class="fa fa-plus-circle" ></i>Ajouter une réponse</button>&nbsp;<br>' +
                            '</div>' +
                            '<div class="col-lg-12" id="quizzreponse_'+obj[i].id+'">'+
                            '<div class="col-lg-6" id="rep_'+idd+'" style="font-weight:normal">Réponse : ' +
                            '<input type="text" style="width:60% !important" class="form-control" id="reponsee_' + randomized + '"><br>' +
                            '</div>' +
                            '<div class="col-lg-6"><br>' +
                            '<input type="radio" id="group_'+idd+'" name="groupe_'+obj[i].id+'">' +
                            '</div></div>');

                    general++;



                }
            }
        });



$('#finished').click(function() {
$(':radio').each(function() {

   console.log($(this.context));
});


});


    });
    function addQ(quizz,random) {
        var id = event.target.id;
        $('#quizzdemerde_'+quizz).after(
                '<div class="col-lg-12" id="quizzreponse_'+quizz+'">'+
                '<div class="col-lg-6" style="font-weight:normal">' + 'Réponse : ' +
                '<input type="text" style="width:60% !important" class="form-control" id="reponse_' + Math.floor((Math.random() * 10) + Math.random() * 100 + Math.random() * 10 / Math.random()) + '">' +
                '<br>' +
                '</div>' +
                '<div class="col-lg-6"><br>' +
                '<input type="radio" name="groupe_' +random + '">' +
                '</div></div>');


    }


</script>