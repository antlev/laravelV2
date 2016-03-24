<?php $__env->startSection('content'); ?>
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
                    <button class="btn btn-success pull-right"><i class="fa fa-next"></i>Suivant</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>"></script>

<script>
    $( document ).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            url: 'question',
            type: "post",
            success: function(data){
                obj = $.parseJSON(data);

                for(var i=0;i<obj.length;i++) {
                    $('#lastquizz').before('<div class="col-lg-12" id="quizz_'+i+'" style="font-weight:bold"> Question ' +  obj[i].id + ' : ' + obj[i].nom + '? &nbsp;<button class="btn btn-default" id="addQuestion_'+obj[i].id+'"><i style="color:green;cursor:pointer" class="fa fa-plus-circle" ></i>Ajouter une réponse</button>&nbsp;<br> <div class="col-lg-6" style="font-weight:normal">Réponse: <input type="text" style="width:60% !important" class="form-control" id="reponse_'+obj[i]+'"><br></div><div class="col-lg-6"><br><input type="checkbox"></div></div>');
                    var j = i;

                    $('[id^=addQuestion]').click(function() {

                            var inputs = $(this).next(':input');
                            $('#' + inputs.context.id.replace('quizz','reponse')).after('<div class="col-lg-12" id="quizz_'+$(this).closest('button').attr('id').replace('addQuestion_','')+'" style="font-weight:bold"> <div class="col-lg-6" style="font-weight:normal">Réponse : <input type="text" style="width:60% !important" class="form-control" id="reponse_"><br></div><div class="col-lg-6"><br><input type="checkbox"></div></div>');
                            die();

                    });
                }
            }
        });


        $('button').each(function () {
            var addone = parseInt(previous) + 1;
            var i = 1;
//$('#lastquizz').before('<br>Réponse '+addone+': <input type="text" style="width:60% !important" class="form-control" id="reponse_'+addone+'"><br></div><div class="col-lg-6"><br><button id="'+addone+'" class="BonneReponse btn btn-success">Bonne réponse</button></div></div>');

            i++;

            $($('#lastquizz').after().attr('id')).after('<div class="col-lg-12" id="quizz_' + i + '" style="font-weight:bold"> <div class="col-lg-6" style="font-weight:normal">Réponse : <input type="text" style="width:60% !important" class="form-control" id="reponse_' + i + '"><br></div><div class="col-lg-6"><br><button id="' + addone + '" class="BonneReponse btn btn-success">Bonne réponse</button></div></div>');

        });

    });



</script>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>