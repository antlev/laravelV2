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

    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
      <div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'>
        </br>
        <h2>Vos messages</h2>
        </br>   
      </div>
    </div>




    <?php $id = 0 ?> <!-- Variable $id permettant de compter le nombre de messages affiché, permettant d'avoir un id -->
    @foreach($posts as $post) <!-- On affiche les catégories -->
    <div class="row">
      <div class=" col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
        <h3>Message {{$id}}
      </div>
      <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info">
        <?php $messagExist = 1 ?> <!-- Si on affiche un message on met cette variable à 1 -->
        <div class="panel-body panel-info" style="min-height:70px">
          <h4> {{$post->post_texte}}  </h4>
          <?php $id++ ?>   <!-- On incrémente la variable id à chaque post -->
        </div>
        

        <div class="panel-footer panel-info" style="height:55px" >
          <div class="col-lg-7 col-md-6 col-sm-6 col-xs-7"> 
            <h5>posté le {{$post->post_time}} par {{Auth::getPrenombyId($post->post_createur)}} {{Auth::getNombyId($post->post_createur)}} </h5>
          </div>
          @if(Auth::isAdmin())
            <div class="col-lg-5 col-md-6 col-sm-6 col-xs-5">  
              <!-- Button 'Voir la discussion' -->
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <a href="{{url('forum/'.$postCat[$id-1].'/'.$post->post_topic_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-success" style="margin-left:15px">Voir la discussion</a> 
              </div> 
              <!-- Button 'Editer un message' -->
              <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <a href="{{url('forum/'.$postCat[$id-1].'/'.$post->post_topic_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-warning" style="margin-left:15px">Editer</a> 
              </div>
              <!-- Button 'Supprimer un message' -->
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <button id="supPost{{$id}}" class="btn btn-danger" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button> 
              </div>
            </div>
          @else(Auth::id() == $post->post_createur)
            <div class="col-lg-3 col-md-3 col-xs-3 panel-footer pull-right">   
              <a href="{{url('forum/'.$postCat[$id-1].'/'.$post->post_topic_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-danger" style="margin-left:15px">Editer</a> 
              <button id="supPost{{$id}}"  href="{{url('forum/'.$postCat[$id-1].'/'.$post->post_topic_id.'/editPost')}}" class="btn btn-warning" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button>
            </div>
          @endif
        </div>
      </div>
    </div>



    <script>
      $('#supPost{{$id}}').click(function() {
        alert('ok');
        $.ajax({
            url: '{{$post->post_topic_id}}/supPost',
            type: "post",
            data: {'postId': $(this).attr('data-id') },
            success: function(data){
              window.location.href = ""; // On redirige sur la même page
            }
        });  
      });
    </script>
  @endforeach

      </tbody>
    </table>
  </body>

  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>

  </footer>
</html> 
