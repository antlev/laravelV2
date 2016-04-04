<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
    <div class="container" style="margin-top: 35px">
      <h1 onclick="location.href='{{url('forum')}}'" >Admin du Forum De La Maison Des Ligues</h1>
    </div>

  </br>
  </br>  


  </head>
  <body>
    <div>
      <h2 class="col-lg-offset-1 col-lg-11">Supprimer tous les posts d'un utilisateur</h2>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par id  du créateur</h4>
        </div>
        <input class="col-lg-8" rows="10" id="idToSup" class="form-control"></input>
        <button  id="supById" class="btn btn-warning col-lg-offset-1 col-lg-1">Supprimer</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par pseudo  du créateur</h4>
        </div>
        <input class="col-lg-8" rows="10" id="pseudoToSup" class="form-control"></input>
        <button  id="supByPseudo" class="btn btn-warning col-lg-offset-1 col-lg-1">Supprimer</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Prenom du créateur</h4>
        </div>
        <input class="col-lg-3" rows="10" id="surnameToSup" class="form-control"></input>
        <div class="col-lg-1 col-lg-offset-1 ">
          <h4>Nom  du créateur</h4>
        </div>
        <input class="col-lg-3" rows="10" id="nameToSup" class="form-control"></input>
        <button id="supByName" class="btn btn-warning col-lg-offset-1 col-lg-1">Supprimer</button>
      </div>
    </div>

    <div>
      <h2 class="col-lg-offset-1 col-lg-11">Afficher tous les posts d'un utilisateur</h2>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par id du créateur</h4>
        </div>
        <input id="idToPrint" class="col-lg-8" rows="10"  class="form-control"></input>
        <button id="printById" class="btn btn-primary col-lg-offset-1 col-lg-1">Afficher</button>
      </div>

      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par pseudo du créateur</h4>
        </div>
        <input id="pseudoToPrint" class="col-lg-8" rows="10"  class="form-control"></input>
        <button id="printByPseudo" class="btn btn-primary col-lg-offset-1 col-lg-1">Afficher</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Prenom du créateur</h4>
        </div>
        <input class="col-lg-3" rows="10" id="surnameToPrint" class="form-control"></input>
        <div class="col-lg-1 col-lg-offset-1 ">
          <h4>Nom du créateur</h4>
        </div>
        <input class="col-lg-3" rows="10" id="nameToPrint" class="form-control"></input>
        <button id="printByName" class="btn btn-primary col-lg-offset-1 col-lg-1">Afficher</button>
      </div>
    </div>

        <div>
      <h2 class="col-lg-offset-1 col-lg-11">Afficher un post</h2>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par id du post</h4>
        </div>
        <input id="postIdToPrint" class="col-lg-8" rows="10"  class="form-control"></input>
        <button id="printByPostId" class="btn btn-primary col-lg-offset-1 col-lg-1">Afficher</button>
      </div>

      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-lg-1">
          <h4>Par date</h4>
        </div>
        <input id="dateToPrint" class="col-lg-8" rows="10"  class="form-control"></input>
        <button id="printByDate" class="btn btn-primary col-lg-offset-1 col-lg-1">Afficher</button>
      </div>
    </div>

    <div class="col-lg-offset-9">
      </br>
      </br>
      <a href="{{url('forum')}}" class="btn btn-success" style="margin-left:15px"> Revenir à l'index du forum</a>
    </div> 
  </body>
</html> 

<script>

    $(function(){
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      
      $('#supById').click(function() {
        if( $('#idToSup').val() == ''){
          alert('Please enter an id')
        } else {
                  $.ajax({
          url: './admin/supById',
          type: "post",
          data: {'idToSup': $('#idToSup').val() },
          success: function(data){ 
            if(data == 0){
              alert("Aucun messages correspondant à la requête n'a pu être supprimé")
            } else {
              //TODO afficher id
              alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
            }
          } 
        });
        }      
      });

      $('#supByName').click(function() {
        if( $('#surnameToSup').val() == '' || $('#nameToSup').val() == '' ){
          alert('Veulliez entrer un nom et un prénom');
        } else {
          $.ajax({
            url: './admin/supByName',
            type: "post",
            // TODO TOCHECK
            data: {'nameToSup': $('#nameToSup').val(), 'surnameToSup': $('#surnameToSup').val() },
            success: function(data){
              console.log(data); 
              if(data == -1){
                alert("Plusieurs utilisateur correspondent veuillez supprimer avec l'id");
              } else if (data == 1) {
                alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });

      $('#supByPseudo').click(function() {
        if( $('#pseudoToSup').val() == ''){
          alert('Veulliez entrer un pseudo svp');
        } else {
          alert('toto');
          $.ajax({
            url: './admin/supByPseudo',
            type: "post",
            // TODO TOCHECK
            data: {'pseudoToSup': $('#pseudoToSup').val()},
            success: function(data){
              console.log(data); 
              if (data == 1) {
                alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });

      $('#printById').click(function() {
        if( $('#idToPrint').val() == ''){
          alert('Please enter an id')
        } else {
                  $.ajax({
          url: './admin/printById',
          type: "post",
          data: {'idToPrint': $('#idToPrint').val() },
          success: function(data){ 
            if(data == 0){
              alert("Aucun messages correspond à la requête")
            } else {
              //TODO afficher id
              alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
            }
          } 
        });
        }      
      });


    $('#printByName').click(function() {
        if( $('#surnameToPrint').val() == '' || $('#nameToPrint').val() == '' ){
          alert('Veulliez entrer un nom et un prénom');
        } else {
          $.ajax({
            url: './admin/printByName',
            type: "post",
            // TODO TOCHECK
            data: {'nameToPrint': $('#nameToPrint').val(), 'surnameToPrint': $('#surnameToPrint').val() },
            success: function(data){
              console.log(data); 
              if(data == -1){
                alert("Plusieurs utilisateur correspondent veuillez utiliser l'id");
              } else if (data == 1) {
                alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });

  });

</script>