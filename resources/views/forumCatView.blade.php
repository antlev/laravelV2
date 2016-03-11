<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
    <div class="col-md-6">
      <img src="{{asset('img/logo.png')}}" style="width:8%">
    <h1 href="{{url('forum')}}" >Forum De La Maison Des Ligues</h1>
    </div>
    <ol class="breadcrumb pull-right where-am-i">
      <li><a href="#">Forums</a></li>
      <li class="active">List of topics</li>
    </ol>
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
 

        <td class="cell-stat text-center hidden-xs hidden-sm">post: {{$topic_as->topic_post}}</td>
        <td class="cell-stat hidden-xs hidden-sm">last_post: {{$topic_as->topic_last_post}}</td>
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

