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
  <div>
    <h2 class="col-lg-offset-1 col-lg-11">Supprimer tous les posts d'un utilisateur</h2>
    <div class="col-lg-offset-1 col-lg-11">
      <div class="col-lg-1">Par id</div>
      <input class="col-lg-8 " rows="10" id="msgToSend" class="form-control"></input>
    </div>
    <div class="col-lg-offset-1">
      <div class="col-lg-1">Par nom</div>
      <input class="col-lg-8" rows="10" id="msgToSend" class="form-control"></input>
    </div>
  </div>

  <div>
    <h2 class="col-lg-offset-1 col-lg-11">Afficher tous les posts d'un utilisateur</h2>
    <div class="col-lg-offset-1 col-lg-11">
      <div class="col-lg-1">Par id</div>
      <input class="col-lg-8 " rows="10" id="msgToSend" class="form-control"></input>
    </div>
    <div class="col-lg-offset-1">
      <div class="col-lg-1">Par nom</div>
      <input class="col-lg-8" rows="10" id="msgToSend" class="form-control"></input>
    </div>
  </div>

  <div class="col-lg-12">
    </br>
    <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:15px"> Revenir à l'index du forum</a>
  </div> 








  </body>
</html> 

