<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <link href="laravel/public/css/bootstrap.css" rel="stylesheet">
    <link href="{{('/laravel/public/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/forum.css')}}">
    
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
    <img src="{{asset('img/logo.png')}}" style="width:8%">
    <h1 href="{{url('forum')}}" >Forum De La Maison Des Ligues</h1>
  </div>
  <ol class="breadcrumb pull-right where-am-i">
    <li><a href="#">Forums</a></li>
    <li class="active">List of topics</li>
  </ol>
  <div class="clearfix"></div>
</div>
<h4 style="margin-left:15px">Vous êtes dans la catégorie <a href="{{url('forum/'.$cat)}}">{{$catName}}</a></h4></br>
<h4 style="margin-left:15px">Titre du Topic : </h4>

  <table class="table forum table-striped">
    <thead>
      <tr>
        <th class="cell-stat"></th>
        <input class="form-control" style="width:85%;margin-left:10px;margin-right:10px!important" rows="6" placeholder="Ecrivez ce que vous voulez"> </input> </br>
        <h4 style="margin-left:15px">Message : </h4>
        <textarea class="form-control" style="width:85%;margin-left:10px;margin-right:10px!important" rows="6" placeholder="Ecrivez ce que vous voulez"> </textarea>
      </tr>
    </thead>
    
      <tbody>

      </tbody></br>
          <a href="{{url('forum/'.$cat)}}" class="btn btn-warning" style="margin-left:15px"> Revenir aux catégories </a>
          <a href="{{url('forum/'.$cat)}}" class="btn btn-info" style="margin-left:15px">Créer le Topic et Poster le Message </a>
  </body>
</html> 

