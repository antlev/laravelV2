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


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
      <img src="{{asset('img/logo.png')}}" style="width:8%">
    <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
  </div>
</div>

<!-- Menu de navigation -->
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle pull-right" type="button" data-toggle="dropdown" style="margin-right:20px">Navigation Forum
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li class="dropdown-header" ><a style="font-weight:bold" href="{{url('forum')}}">Index</a></li>
    @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
      <li class="dropdown-header"><a href="{{url('forum/'.$cats->cat_id.'/')}}">{{$cats->cat_nom}}</a></li>
      @foreach($topic as $topic_as) <!-- On affiche les catégories -->
        @if($topic_as->topic_cat==$cats->cat_id)
          <li><a href="{{url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)}}">{{$topic_as->topic_titre}}</a></li>
        @endif
      @endforeach
      </li>
    @endforeach
  <ul>
</div>


    <div class="clearfix"></div>
  </div>

  <table class="table forum table-striped">
    <thead>
      <tr>
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="fa fa-pencil-square-o" style="font-size:25px;color:black !important;margin-left:30px"></a>
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="btn btn-info" style="margin-left:15px">Créer un Nouveau Topic </a>
        @if(!empty($topic))
        <th class="cell-stat"><h3>{{$catName}}</h3></th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
        @endif      
      </tr>
    </thead>


    <tbody>
      @if(empty($topic))
        <tr><h3> Aucun topics n'a été créé dans la catégorie {{$catName}} </h3></tr>
      @else

      @foreach($topic as $topic_as) <!-- On affiche les catégories -->
      <tr>
        <td>
          <h4><a href="{{url('forum/'.$cat.'/'.$topic_as->topic_id)}}">{{$topic_as->topic_titre}}</a></h4>
        </td>
 

        <td class="cell-stat text-center hidden-xs hidden-sm">post: 0</td>
        <td class="cell-stat hidden-xs hidden-sm">last_post: 0</td>
      </tr>
      @endforeach
      @endif

    </tbody>
  </table>


  </body>

  <footer>
    <i class="fa fa-facebook-5x"></i>
    <a href="{{url('forum')}}" class="btn btn-warning">Revenir à l'index </a>
    <h2> [[Footer]]</h2>
  </footer>
</html> 

