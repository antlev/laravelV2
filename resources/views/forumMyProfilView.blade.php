<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>
  <body>
    <div class="page-header page-heading col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div>
        <img class="col-lg-offset-2 col-md-offset-1 col-md-1 hidden-sm hidden-xs" src="{{asset('img/logo.png')}}" style="width:6%">
        <h1 class="col-lg-10 col-md-7 col-sm-offset-2 col-xs-12" onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
      </div>
    </div>
    </br>

    <div class="col-lg-offset-1 col-lg-11 col-md-offset-1 col-md-11 col-sm-offset-1 col-sm-11">
      <h2>Profil de : {{Auth::getPrenomById(Auth::id())}} {{Auth::getNomById(Auth::id())}}
    </div>
    <div class='col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-10  col-sm-offset-2 col-xs-offset-2'>
      <div class="col-lg-3 col-md-5 pull-left">
        <h3>Nombre de messages postés : </h3>
      </div>
      <div class="col-lg-2 col-md-3">
        <h4>42 </h4>
      </div>
    </div>
    <div class='col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-10  col-sm-offset-2 col-xs-offset-2'>
      <div>
        <h3>Nombre de Topics créés : </h3>
      </div>
      <div class="col-lg-2 col-md-3">
        <h4>42 </h4>
      </div> 
    </div>
  </body>





  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>

  </footer>
</html> 

