<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <link href="laravel/public/css/bootstrap.css" rel="stylesheet">
    <link href="{{('/laravel/public/css/bootstrap.min.css')}}" rel="stylesheet">
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
    <h1 href="{{url('forum')}}" >Forum De La Maison Des Ligues</h1>
    <ol class="breadcrumb pull-right where-am-i">
      <li><a href="#">Forums</a></li>
      <li class="active">List of topics</li>
    </ol>
    <div class="clearfix"></div>
    <h3><a href=""> Topic : {{$topic[0]->topic_titre}}</a></h3>
  </div>


    <?php $messagExist = 0 ?> <!-- On initialise messagExist à 0 -->
  @foreach($posts as $post) <!-- On affiche les catégories -->
  <div class="panel panel-default">
    @if($post->post_topic_id==$topic[0]->topic_id)
          <?php $messagExist = 1 ?> <!-- Si on affiche un message on met cette variable à 1 -->
    <div class="panel-body panel-info">
      {{$post->post_texte}}      
    </div>
    <div class="panel-footer">créé le {{$post->post_time}} par {{$post->post_createur}}</div>
    @endif
    @if($messagExist==0) <!-- Si aucun message ne doit être affiché -->
      <tr><h3> Aucun message n'est sauvegardé pour ce topic '{{$topic[0]->topic_titre}}' </h3></tr>
    @endif
  </div>
  @endforeach


  <a href="{{url('forum/'.$cat.'/')}}" class="btn btn-warning" style="margin-left:15px">Revenir aux catégories</a>
  <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/newMessage')}}" class="btn btn-info" style="margin-left:15px">Ecrire un nouveau message </a>

  </body>
</html> 

