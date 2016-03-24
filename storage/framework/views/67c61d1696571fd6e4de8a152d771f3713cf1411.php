<?php $__env->startSection('content'); ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css" />
    <link href="<?php echo e(asset('css/responsive-calendar.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <style>

  body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
  }

  #calendar {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 10px;
  }
  .ui-autocomplete {
  z-index: 215000000 !important;
    }

</style>
    <script type="text/javascript" charset="utf-8">
    var data = {
        name: '',
        id: '',
        action: ''
    };
        $(document).ready(function() {
            function timeRefresh(timeoutPeriod) 
            {
                setTimeout("location.reload(true);",timeoutPeriod);
            }
            var table = $('#vue_list').DataTable({
                "language":{
                    "url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
                },
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'width': '1%',
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                }],
                'order': [[1, 'desc']],
            });
            var pathArray = window.location.pathname.split( '/' );
            data.table = pathArray[pathArray.length-1];
            // $('#vue_list tbody').on('click', 'button', function () {
            //     var data1 = table.row( $(this).parents('tr') ).data();
            //     data.data = data1;
            //     console.log(data);
            //     $.ajax({
            //         type: 'GET',
            //         url: '../supp_view',
            //         data: data,
            //     });
            //     location.reload();
            // });

            $('#delete').on('click', function(){
                var i= 0;
                if(confirm("Etes vous sur de vouloir supprimer ces informations ?")){
                    $( table.cells().nodes() ).find(':checkbox').each(function(){
                        if($(this)[0].checked){
                            i=1;
                            data.data = table.row($(this).parents('tr')).data();
                            $.ajax({
                                type: 'GET',
                                url: '../supp_view',
                                data: data,
                            });
                        }
                    });
                }
                // On refresh seulement si on à coché une valeur a supprimer
                if(i == 1){timeRefresh('1000');}                
            });

            $('#select_all').change(function(){
                $('#delete').prop('disabled', !$(this).is(':checked'));
                $('#edit').prop('disabled', true);
                var cells = table.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
            });

            $('.dt-body-center').on('click', function(){
                // var i=0;
                var i = $( "input:checked" ).length;

                if(i == 0){$('#delete, #edit').prop('disabled', true);}
                else if(i == 1){$('#delete, #edit').prop('disabled', false);}
                else{
                    $('#delete').prop('disabled', false);
                    $('#edit').prop('disabled', true);
                }
            });

            $('#vue_list tbody').on('dblclick', 'td', function(){
                data1 = table.row( $(this).parents('tr') ).data();
                data.id = data1[1];
                //Si l'input existe déjà (on redouble-clique sur le TD) on fait rien.
                var input = $(this).find('input');
                if(input.length < 1){
                    var origin = $(this).find('span');
                    if(origin.context.className.indexOf('nok') != -1){return;}
                    data.name = origin.context.id;
                    //Quick security, si le span n'existe pas, je le créé.
                    if(origin.length === 0){
                        $(this).wrapInner('<span></span>');
                        origin = $(this).find('span');
                    }

                    //Je supprime toute selection faite causé par double clique
                    if (window.getSelection){
                        window.getSelection().removeAllRanges();
                    } 
                    else if (document.selection) {
                        document.selection.empty();
                    }

                    //on supprime les précedents input s'ils existent
                    $('.editspan').remove();
                    $('span').show();

                    //on cache la valeur
                    origin.hide();
                    // console.log(origin.context.id);
                    //et on crée l'input
                    if( origin.context.id == 'sport' ){
                        $(this).append('<span class="editspan"><select id="select"><option></option><option value="Football">Football</option><option value="Basket">Basketball</option><option value="Handball">Handball</option><option value="Tennis">Tennis</option><option value="Rugby">Rugby</option><option value="Aucun">Aucun</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else if(origin.context.id == 'type' && data.table == 'planning'){
                        $(this).append('<span class="editspan"><select id="select"><option value="Entrainement">Entrainement</option><option value="Reunion">Réunion</option><option value="Match">Match</option><option value="Tournoi">Tournoi</option><option value="Fête">Fête</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                        // alert(origin.text());
                    }
                    else if(origin.context.id == 'role'){
                        $(this).append('<span class="editspan"><select id="select"><option value="Invités">Invités</option><option value="Entraineurs">Entraineurs</option><option value="Membres">Membres</option><option value="Joueurs">Joueurs</option><option value="Direction">Direction</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                        // console.log(origin);
                    }
                    else if (origin.context.id == 'categorie'){
                        $(this).append('<span class="editspan"><select id="select"><option></option><option value="Junior">Junior</option><option value="U-11">U-11</option><option value="U-13">U-13</option><option value="U-15">U-15</option><option value="U-17">U-17</option><option value="U-20">U-20</option><option value="Senior">Senior</option><option value="Loisir">Loisir</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else {
                        $(this).append('<span class="editspan"><input style="width: 150px;" value="'+origin.text()+'"></input><span style="cursor: pointer" class="update">✓</span></span>');
                    }
                }
            });
            //quand on appuit sur le bouton d'edit
            $('#vue_list tbody td').on('click', '.update', function(){
                editAjax($(this));
            });

            //quick edit en cas de double clique
            function editAjax(item){
                // console.log(item.prev().val());
                // console.log(data);
                if(data.name == 'datenaiss'){
                    if(item.prev().val() != ""){
                        date = item.prev().val()
                        split = date.split('/');
                        console.log(split.length);
                        if(split.length != 3){err('La date est mal rempli veuillez respecter le format Jour/mois/Année'); return;}
                        else if(split[0].length != 2 || split[1].length != 2 || split[2].length != 4){err('La date est mal rempli veuillez respecter le format Jour/mois/Année'); return;}
                        else{
                            var d = new Date();
                            year = d.getFullYear()-5;
                            if(split[0] == "00" || split[0] > 31){err('Le nombre de jour défini est faux');return;}
                            if(split[1] == "00" || split[1] > 12){err('Le nombre de mois est compris entre 01 et 12');return;}
                            if(split[2] < 1920  || split[2] > year){err('L\' année saisie est incorrecte ');return;}
                        }
                    }
                    else{err('La date est vide');return;}
                }
                data.name = data.name == 'role' ? 'id_role' : data.name;
                $.get("../update", {
                    value: item.prev().val(),
                    id: data.id,
                    name: data.name,
                    table: data.table,
                }).done(function() {
                    // location.reload();
                    timeRefresh('1000');
                });
            }
            function err(text){
                document.getElementById('error').className = 'alert alert-danger';
                document.getElementById('error').style.display = "";
                document.getElementById('error').innerHTML = '<strong>Attention!</strong> '+text;
            }

            function autocomplete(field, table, name, type,  nom){
                datas = {'action': 'autocomplete', type: type, name: name, table: table, nom:nom};
                $.ajax({
                  type: "GET",
                  url: '../lieu',
                  data: datas,
                  success:function(data){
                    console.log(data);
                    data = JSON.parse(data);
                    var lieu = new Array();
                    for(i in data){
                      lieu.push(data[i].nom);
                    }
                    $("#"+field).autocomplete({
                      source: lieu
                    });
                  }
                });
            }
            $('#sport_1').change(function(){
              autocomplete('lieu_name_1', 'lieux','sport', $('#sport_1').val(), 'nom');
            });
            $('#create, #edit').on('click', function(){
                data.action = $(this).context.id == 'create' ? 'insert' : 'update';
                if(data.table == 'user_roles'){
                    autocomplete('name_1', 'users');                     
                }
                else if(data.table == 'participants'){
                    autocomplete('nom_planning_1', 'participants')
                }
            });
            $('#edit').on('click', function(){
                // console.log(table.row( $( "input:checked" ).parents('tr')).context());
                data.action = 'update';
                value = table.row( $( "input:checked" ).parents('tr')).data();
                console.log(value);
                data.id = value[1];
                diff = value.length - $( '#myModal input, #myModal select' ).length;
                if(data.table == 'user_roles'){diff = 4;}
                else if(data.table != 'users'){diff -= 2;}
                // console.log(value.length);
                // console.log($( '#myModal input, #myModal select' ).length);
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    console.log(data2.id+ " index : "+index);
                    $('#'+data2.id).val(value[index+diff]);
                });
                // console.log(table.row( $( "input:checked" ).parents('tr')).ids());
            });
            $('#save').on('click', function(){
                valid = true;
                data.create = [];
                // console.log(data.action);
                // tab = table.column().header();
                // console.log($('#myModal'));
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    id = $(this).parent()[0].id;
                    clas = $(this).parent()[0].className;
                    // console.log(clas+' '+id);
                    // console.log($(this).parent()[0]);
                    // console.log($('#'+data.id));
                    if($('#'+data2.id).val() == '' || (data2.id == 'email_1' && isEmail($('#'+data2.id).val()) == false)){
                        valid = false;
                        if(clas.indexOf('has') == -1){$('#'+id).addClass('has-error');}
                        else if(clas.indexOf('success') != -1){$('#'+id).removeClass('has-success').addClass('has-error');}
                    }
                    else{
                        if(clas.indexOf('has') == -1){$('#'+id).addClass('has-success');}
                        else if(clas.indexOf('error') != -1){$('#'+id).removeClass('has-error').addClass('has-success');}
                        // alert(clas.indexOf('error'));
                        id2 = data2.id.replace('_1', '');
                        data.create.push({
                            value: $('#'+data2.id).val(),
                            name: id2,
                        });
                    }
                });
                function isEmail(myVar){
                    // Définition de l'expression régulière d'une adresse email
                    var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');

                    return regEmail.test(myVar);
                }
                if(valid == true){

                    // console.log(data);
                    $.ajax({
                        url: "../create",
                        type: "get",
                        data : data,
                    });
                    // location.reload();
                    timeRefresh('1000');
                }
           });
        });
    </script>

<body>
    <div class="container">
        <div id="error" style="display:none;" align="left"></div>
        <div id="admin" style="display:none;"><?php echo e(Auth::isAdmin()); ?></div>
        <div class="container">
            <button class="btn btn-danger" id="delete" disabled> Supprimer</button>
            <?php if($table != 'planning'): ?>
                <button class="btn btn-success" id="create" data-toggle="modal" data-target="#myModal">Création</button>
            <?php endif; ?>
            <button class="btn btn-info" id="edit" data-toggle="modal" data-target="#myModal" disabled>Edition</button>
        </div>
        </br>
        <table id="vue_list" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <?php if(Auth::isAdmin()): ?>
                    <th><input id="select_all" value="1" type="checkbox"></th>
                    <?php endif; ?>
                    <?php foreach($names as $nom => $edit): ?>
                        <?php if(strpos($nom,'id') === false): ?>
                            <th><?php echo e(trans("view.$nom")); ?></th>
                        <?php else: ?>
                            <th style="display:none"></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($types as $type): ?>
                <tr>
                    <?php if(Auth::isAdmin()): ?>
                    <td></td>
                    <?php endif; ?>
                    <?php foreach($names as $key => $value): ?>
                        <?php if(strpos($key,'id') === false): ?>
                            <td class="tedit <?php echo e($value); ?>" id ="<?php echo e($key); ?>"><?php echo e($type->$key); ?></td>
                        <?php else: ?>
                            <td style="display:none"><?php echo e($type->$key); ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Modal -->
    <div id="myModal" class="modal " role="form">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo e(trans("view.$table")); ?></h4>
          </div>
          <div class="modal-body">
            <?php foreach($names as $nom => $edit): ?>
                <?php if($edit == 'ok' || ($nom == 'name' && $table == 'user_roles') || ($table == 'participants' && $nom == 'nom_planning')): ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><?php echo e(trans("view.$nom")); ?></label>
                        <div class="col-md-6" id="<?php echo e('class_'.$nom); ?>">
                            <?php if($nom == 'sport'): ?>
                            <select class="form-control" id="<?php echo e($nom.'_1'); ?>">
                                <option></option>
                                <option value="Football">Football</option>
                                <option value="Basket">Basketball</option>
                                <option value="Handball">Handball</option>
                                <option value="Tennis">Tennis</option>
                                <option value="Rugby">Rugby</option>
                                <option value="Aucun">Aucun</option>
                            </select>
                            <?php elseif($nom == 'type'): ?>
                            <select class="form-control" id="<?php echo e($nom.'_1'); ?>">
                                <option></option>
                                <option value="Entrainement">Entrainement</option>
                                <option value="Reunion">Réunion</option>
                                <option value="Match">Match</option>
                                <option value="Tournoi">Tournoi</option>
                                <option value="Fête">Fête</option>
                            </select>
                            <?php elseif($nom == 'categorie'): ?>
                                <select class="form-control" id="<?php echo e($nom.'_1'); ?>">
                                    <option></option>
                                    <option value="Junior">Junior</option>
                                    <option value="U-11">U-11</option>
                                    <option value="U-13">U-13</option>
                                    <option value="U-15">U-15</option>
                                    <option value="U-17">U-17</option>
                                    <option value="U-20">U-20</option>
                                    <option value="Senior">Senior</option>
                                    <option value="Loisir">Loisir</option>
                                </select>
                            <?php elseif($nom == 'role'): ?>
                                <select class="form-control" id="<?php echo e($nom.'_1'); ?>">
                                    <option></option>
                                    <option value="Invités">Invités</option>
                                    <option value="Entraineurs">Entraineurs</option>
                                    <option value="Membres">Membres</option>
                                    <option value="Joueurs">Joueurs</option>
                                    <option value="Direction">Direction</option>
                                </select>                                
                            <?php else: ?>
                                <input type="text" class="form-control" id="<?php echo e($nom.'_1'); ?>">
                            <?php endif; ?>
                        </div>
                    </div></br>
                <?php endif; ?>
            <?php endforeach; ?>

          </div>
          <div class="modal-footer">
            <div id="error" style="display:none;" align="left"></div>
            <div class="btn-group">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
              <button type="button" class="btn btn-primary" id="save">Sauvegarde</button>
            </div>
          </div>
        </div>
      </div>

    <script type="text/javascript">
        $('#vue_list')
            .removeClass('display')
            .addClass('table table-striped table-bordered');
        
    </script>
</body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>