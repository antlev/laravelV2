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

    <ul class="nav nav-pills col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
      <li role="presentation" class="active"><a href="{{url('forum/')}}">Home</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myProfil')}}">Profil</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myPosts')}}">Mes Messages</a></li>
    </ul>
    </br>
    </br>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
      <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="fa fa-pencil-square-o pull-right" style="font-size:25px;color:black !important;margin-left:30px"></a>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="btn btn-info col-xs-12" style="margin-left:15px">Créer un Nouveau Topic </a>
      </div>
    </div>

    <!-- Menu de navigation -->
    <div class="dropdown col-lg-6 col-md-6 col-sm-6">
      <button class="btn btn-primary dropdown-toggle col-lg-offset-8 col-md-offset-7 col-sm-offset-6 col-lg-3 col-md-4 col-sm-5 col-xs-12" type="button" data-toggle="dropdown">Navigation Forum
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li class="dropdown-header" >
          <a style="font-weight:bold" href="{{url('forum')}}">Index</a>
        </li>
        @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
          <li class="dropdown-header">
            <a style="font-weight:bold" href="{{url('forum/'.$cats->cat_id.'/')}}">
              <h4>{{$cats->cat_nom}}</h4>
            </a>
          </li>
          @foreach($topics as $topic_as) <!-- On affiche les catégories -->
            @if($topic_as->topic_cat==$cats->cat_id)
              <li>
                <a href="{{url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)}}">
                  <h5>{{$topic_as->topic_titre}}</h5>
                </a>
              </li>
            @endif
          @endforeach
        @endforeach
      </ul>
    </div>
    <table class="table forum table-striped">
      <thead>
        <tr>
          
          @if(!empty($topic))
            <th class="cell-stat">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Catégorie : {{$catName}}</h3>
              </div>
            </th>
            <th class="cell-stat text-center hidden-xs">Posts</th>
            <th class="cell-stat-2x hidden-sm hidden-xs">Last Post</th>
          @endif      
        </tr>
      </thead>
      <tbody>
        <?php $compteur = 0 ?>
        @if(empty($topic))
          <tr>
            <h3> Aucun topics n'a été créé dans la catégorie {{$catName}} </h3>
          </tr>
        @else
          <div id="page">
            @foreach($topic as $topic_as) <!-- On affiche les catégories -->
              <tr id="{{$topic_as->topic_id}}">
                <td>
                  <h4>
                    <a href="{{url('forum/'.$cat.'/'.$topic_as->topic_id)}}"  style="margin-left:20px" >{{$topic_as->topic_titre}}</a>
                  </h4>
                </td>
                <td class="cell-stat text-center hidden-xs">{{$nbPost[$compteur]}}</td>
                <!-- TODO error on $lastPostCreator[$compteur] -->
                <td class="cell-stat hidden-sm hidden-xs">écrit par :</td>
              </tr>
              <?php $compteur++ ?>
            @endforeach
          </div>
        @endif
      </tbody>
    </table>
  </body>

  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <button id="next" class="btn btn-success pull-right" style="margin-right:20px">Suivant</button>
      </br>
    </div>
  </footer>
</html> 

<script>
    $(function(){
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      $('#next').click(function() {
        alert('next');
        $.ajax({
          url: './{{$cat}}/next',
          type: "post",
          data: {'lastTopicPrinted': $('table tr:last').attr('id') },
          success: function(data){ 
            $('#page').html(data);
          }
        });  
      });      
    })

</script>

