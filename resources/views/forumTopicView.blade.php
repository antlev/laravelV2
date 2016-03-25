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

  <body>
    <div class="container" style="margin-top: 35px">
      <div class="page-header page-heading">
        <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
      </div>
    </div>

    <div>
      <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <h2>
          <div class="col-lg-10 hidden-sm hidden-xs">Catégorie : {{$catName}}</div>
          <div class="hidden-lg hidden-md">{{$catName}}</div>
        </h2>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <!-- Menu de navigation -->
        <div class="dropdown col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
          <button class="btn btn-primary dropdown-toggle pull-right" type="button" data-toggle="dropdown">Navigation Forum
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li class="dropdown-header" >
              <a style="font-weight:bold" href="{{url('forum')}}">Index</a>
            </li>
            @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
              <li class="dropdown-header">
                <a href="{{url('forum/'.$cats->cat_id.'/')}}">{{$cats->cat_nom}}</a>
              </li>
              @foreach($topic as $topic_as) <!-- On affiche les catégories -->
                @if($topic_as->topic_cat==$cats->cat_id)
                  <li>
                    <a href="{{url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)}}">{{$topic_as->topic_titre}}</a>
                  </li>
                @endif
              @endforeach
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
      <h3>
        <div> Topic : {{$topic[0]->topic_titre}}</div>
      </h3>
    </div>

      

    <?php $messagExist = 0 ?> <!-- On initialise messagExist à 0 -->
    <?php $id = 0 ?> <!-- Variable $id permettant de compter le nombre de messages affiché, permettant d'avoir un id -->
    
    <div class="col-lg-12 col-md-12 col-xs-12 panel panel-info">
      @foreach($posts as $post) <!-- On affiche les catégories -->
        @if($post->post_topic_id==$topic[0]->topic_id)
          <?php $messagExist = 1 ?> <!-- Si on affiche un message on met cette variable à 1 -->
          <div class="panel-body panel-info" style="min-height:70px">
            <div> {{$post->post_texte}}  </div>
            <?php $id++ ?>   
          </div>
          

          <div class="panel-footer panel-info" style="height:55px" >
            <div class="col-lg-10 col-md-8 col-sm-8 col-xs-8"> 
              <div>créé le {{$post->post_time}} par {{Auth::getPrenombyId($post->post_createur)}} {{Auth::getNombyId($post->post_createur)}} </div>
            </div>
            @if(Auth::isAdmin())
              <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 pull-right">   
                <!-- Button 'Editer un message' -->
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/'.$post->post_id.'/editMessage')}}" class="btn btn-warning" style="margin-left:15px">Editer</a> 
                </div>
                <!-- Button 'Supprimer un message' -->
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <button id="supMessage{{$id}}" class="btn btn-danger" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button> 
                </div>
              </div>
            @else(Auth::id() == $post->post_createur)
              <div class="col-lg-3 col-md-3 col-xs-3 panel-footer pull-right">   
                <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/'.$post->post_id.'/editMessage')}}" class="btn btn-danger" style="margin-left:15px">Editer</a> 
                <button id="supMessage{{$id}}"  href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/editMessage')}}" class="btn btn-warning" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button>
              </div>
            @endif
          </div>



        @endif
        <script>
          $('#supMessage{{$id}}').click(function() {
            $.ajax({
                url: '{{$topic[0]->topic_id}}/supMessage',
                type: "post",
                data: {'post_id': $(this).attr('data-id') },
                success: function(data){
                  window.location.href = ""; // On redirige sur la même page
                }
            });  
          });
          $('#editMessage{{$id}}').click(function() {
            $.ajax({
                url: '{{$topic[0]->topic_id}}/editMessage',
                type: "post",
                data: {'post_id': $(this).attr('data-id') },
                success: function(data){
                  window.location.href = "";
                }
            });  
          });
        </script>
      @endforeach
    </div>

    @if($messagExist==0) <!-- Si aucun message ne doit être affiché -->
      <tr><h3> Aucun message n'est sauvegardé pour ce topic '{{$topic[0]->topic_titre}}' </h3></tr>
    @endif



  </body>
  <footer>
    <!-- Button 'Revenir aux catégories' -->
    <div class="col-lg-9 col-md-8 col-sm-6 col-xs-12">
      <a href="{{url('forum/'.$cat.'/')}}" class="btn btn-warning" style="margin-left:15px">Revenir aux catégories</a>
    </div>
    <!-- Button 'Ecrire un nouveau message' -->
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
      <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/newMessage')}}" class="btn btn-info" style="margin-left:15px">Ecrire un nouveau message </a>
    </div>
  </footer>
</html> 

<script>
  $(function() { 
    
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  });
</script>