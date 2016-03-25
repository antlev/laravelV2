@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

    <div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <br>
                <div class="panel panel-header" style="text-align: center"><br><br><b>Nom du quizz:</b> <br><b>Thème</b> :</div>
                <div class="panel-body">

                    <div id="quizzgame">


                    </div>

<br>

                    &nbsp;
                    <div id="lastquizz"></div>
                    <!-- <pre>{{ var_dump(Auth::user())}} </pre> -->
                </div>
                <button class="btn btn-success pull-right" id="gotoscore"><i class="fa fa-next"></i>Suivant</button>
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
        url: 'getgame',
        type: "post",
        success: function(data) {
            alert(data);
            obj = $.parseJSON(data);
            for (var i = 0; i < obj.length; i++) {
                if ($('#quizzgame').text().indexOf("Question " + obj[i].id) != -1) {

                    $('#question_' + obj[i].id).after("<input name='r_"+obj[i].id+"'  type='radio'> " + obj[i].choix+"<br>");
                } else {
                    $('#quizzgame').prepend("<div id='question_" + obj[i].id + "'><br><br><b>Question</b> " + obj[i].id + ": " + obj[i].nom + "?<br><input name='r_"+obj[i].id+"' type='radio'> " + obj[i].choix + "</div>");
                }

            }


        }
    });

$('#gotoscore').click(function() {
    var values = [];
    $("input[name^='r_']").each(function () {
        var item1 = {
            "data": { "question": $(this).prev('input').attr('id'), "reponse": $(this).val() }
        };
        values.push(item1);
    });
    $.ajax({
        url: 'checkanswer',
        type: "post",
        data: JSON.stringify({ "answer": values }) ,
        success: function(data){
        alert(data);
        }
    });
});
    });


</script>
<script src="{{asset('js/bootstrap.js')}}"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
