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
  </head>


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
      <img src="{{asset('img/logo.png')}}" style="width:8%">
      <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
    <p class="lead">Bienvenue sur le forum de la maison des ligues</p>
  </div>
</div>

    <!-- Menu de navigation -->
    <ol class="breadcrumb pull-right where-am-i">
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
    </ol>

  <table class="table forum table-striped">
    <thead>
      @foreach($forum as $forum_as) <!-- On affiche les catégories -->
      <tr>
        <th class="cell-stat"></th>
        <th>
          <h3>{{$forum_as->forum_name}}<br><small>{{$forum_as->forum_desc}}</small></h3>
        </th> 
        <th class="cell-stat text-center hidden-xs hidden-sm">Topics</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
      </tr>
    </thead>


    
      <tbody>
        @foreach($categories as $cat)  <!-- On affiche les sous_catégories -->
          @if($forum_as->forum_id == $cat->forum_id) <!-- Uniquement si elles appartiennent à la catégorie affichée -->
          <tr>
            <td class="text-center"><i class="fa fa-heart fa-2x text-primary"></i></td>
            <td>
               <h4><a href="{{url('forum/'.$cat->cat_id)}}">{{$cat->cat_nom}}</a><br><small>{{$cat->cat_desc}}</small></h4> <!-- href renvoie l'id de la categorie au controleur si l'on clique dessus -->
            </td>
            <td class="text-center hidden-xs hidden-sm"><a href="#"></a></td>
            <td class="text-center hidden-xs hidden-sm"><a href="#">152123</a></td>
            <td class="hidden-xs hidden-sm">by <a href="#">Jane Doe</a><br><small><i class="fa fa-clock-o"></i> 3 months ago</small></td>
          </tr>
          @endif
        @endforeach
      </tbody>
    @endforeach

  </body>
</html> 

