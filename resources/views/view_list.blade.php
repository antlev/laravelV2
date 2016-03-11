@extends('layouts.app')


@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css" />
    <link href="/laravel/public/calendar/css/responsive-calendar.css" rel="stylesheet">
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
        id: ''
    };
        $(document).ready(function() {
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
                if(i == 1){location.reload();}                
            });

            $('#select_all').change(function(){
                var cells = table.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
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

            function inform(text,time) {
                ajaxStatus.showStatus(text);
                window.setTimeout('ajaxStatus.hideStatus()',time);
            };

            //quand on appuit sur le bouton d'edit
            $('#vue_list tbody td').on('click', '.update', function(){
                editAjax($(this));
            });

            //quick edit en cas de double clique
            function editAjax(item){
                console.log(item.prev().val());
                console.log(data);
                if(data.name == 'datenaiss'){
                    if(item.prev().val() != ""){
                        date = item.prev().val()
                        split = date.split('/');
                        console.log(split.length);
                        if(split.length != 3){err('La date est mal rempli veuillez respecter le format DD/MM/YYYY'); return;}
                        else if(split[0].length != 2 || split[1].length != 2 || split[2].length != 4){err('La date est mal rempli veuillez respecter le format DD/MM/YYYY'); return;}
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
                    location.reload();
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
                if(data.table == 'user_roles'){
                    autocomplete('name_1', 'users');                     
                }
                else if(data.table == 'participants'){
                    autocomplete('nom_planning_1', 'participants')
                }
            });
            $('#save').on('click', function(){
                valid = true;
                data.create = [];
                // tab = table.column().header();
                // console.log($('#myModal'));
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    id = $(this).parent()[0].id;
                    clas = $(this).parent()[0].className;
                    // console.log(clas+' '+id);
                    // console.log($(this).parent()[0]);
                    // console.log($('#'+data.id));
                    if($('#'+data2.id).val() == ''){
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
                if(valid == true){

                    console.log(data);
                    $.ajax({
                        url: "../create",
                        type: "get",
                        data : data,
                    });
                    // location.reload();
                }
           });
        });
    </script>

<body>
    <div class="container">
        <div id="error" style="display:none;" align="left"></div>
        <div id="admin" style="display:none;">{{Auth::isAdmin()}}</div>
        <div class="container">
            <button class="btn btn-danger" id="delete"> Supprimer</button>
            <button class="btn btn-success" id="create" data-toggle="modal" data-target="#myModal">Création</button>
            <button class="btn btn-info" id="edit" data-toggle="modal" data-target="#myModal">Edition</button>
        </div>
        </br>
        <table id="vue_list" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    @if(Auth::isAdmin())
                    <th><input id="select_all" value="1" type="checkbox"></th>
                    @endif
                    @foreach($names as $nom => $edit)
                        @if(strpos($nom,'id') === false)
                            <th>{{trans("view.$nom")}}</th>
                        @else
                            <th style="display:none"></th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                <tr>
                    @if(Auth::isAdmin())
                    <td></td>
                    @endif
                    @foreach($names as $key => $value)
                        @if(strpos($key,'id') === false)
                            <td class="tedit {{$value}}" id ="{{$key}}">{{$type->$key}}</td>
                        @else
                            <td style="display:none">{{$type->$key}}</td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
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
            <h4 class="modal-title">{{trans("view.$table")}}</h4>
          </div>
          <div class="modal-body">
            @foreach($names as $nom => $edit)
                @if($edit == 'ok' || ($nom == 'name' && $table == 'user_roles') || ($table == 'participants' && $nom == 'nom_planning'))
                    <div class="form-group">
                        <label class="col-md-2 control-label">{{trans("view.$nom")}}</label>
                        <div class="col-md-6" id="{{'class_'.$nom}}">
                            @if($nom == 'sport')
                            <select class="form-control" id="{{$nom.'_1'}}">
                                <option></option>
                                <option value="Football">Football</option>
                                <option value="Basket">Basketball</option>
                                <option value="Handball">Handball</option>
                                <option value="Tennis">Tennis</option>
                                <option value="Rugby">Rugby</option>
                                <option value="Aucun">Aucun</option>
                            </select>
                            @elseif($nom == 'type')
                            <select class="form-control" id="{{$nom.'_1'}}">
                                <option></option>
                                <option value="Entrainement">Entrainement</option>
                                <option value="Reunion">Réunion</option>
                                <option value="Match">Match</option>
                                <option value="Tournoi">Tournoi</option>
                                <option value="Fête">Fête</option>
                            </select>
                            @elseif($nom == 'categorie')
                                <select class="form-control" id="{{$nom.'_1'}}">
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
                            @elseif($nom == 'role')
                                <select class="form-control" id="{{$nom.'_1'}}">
                                    <option></option>
                                    <option value="Invités">Invités</option>
                                    <option value="Entraineurs">Entraineurs</option>
                                    <option value="Membres">Membres</option>
                                    <option value="Joueurs">Joueurs</option>
                                    <option value="Direction">Direction</option>
                                </select>                                
                            @else
                                <input type="text" class="form-control" id="{{$nom.'_1'}}">
                            @endif
                        </div>
                    </div></br>
                @endif
            @endforeach

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

@endsection