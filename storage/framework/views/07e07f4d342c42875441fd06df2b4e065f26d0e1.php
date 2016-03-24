<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>" rel="stylesheet"> </script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>" rel="stylesheet"> </script>
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>


  <div class="container" style="margin-top: 35px">
    <div class="page-header page-heading">
      <h1 onclick="location.href='<?php echo e(url('forum')); ?>'" >Forum De La Maison Des Ligues</h1>
    </div>
  </div>

<!-- Menu de navigation -->
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle pull-right" type="button" data-toggle="dropdown" style="margin-right:20px">Navigation Forum
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li class="dropdown-header" ><a style="font-weight:bold" href="<?php echo e(url('forum')); ?>">Index</a></li>
      <?php foreach($categories as $cats): ?>  <!-- On affiche les sous_catégories -->
        <li class="dropdown-header"><a href="<?php echo e(url('forum/'.$cats->cat_id.'/')); ?>"><?php echo e($cats->cat_nom); ?></a></li>
          <?php foreach($topic as $topic_as): ?> <!-- On affiche les catégories -->
            <?php if($topic_as->topic_cat==$cats->cat_id): ?>
              <li>
                <a href="<?php echo e(url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)); ?>"><?php echo e($topic_as->topic_titre); ?></a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </li>
      <?php endforeach; ?>
    <ul>
  </div>

  <div class="clearfix">
    <h3>
      <a href=""> Topic : <?php echo e($topic[0]->topic_titre); ?></a>
    </h3>
  </div>

  <?php $messagExist = 0 ?> <!-- On initialise messagExist à 0 -->
  <?php foreach($posts as $post): ?> <!-- On affiche les catégories -->
    <div class="panel panel-default">
      <?php if($post->post_topic_id==$topic[0]->topic_id): ?>
        <?php $messagExist = 1 ?> <!-- Si on affiche un message on met cette variable à 1 -->
        <div class="panel-body panel-info">
          <div> <?php echo e($post->post_texte); ?>  </div>    
        </div>
        <div class="col-lg-10 panel-footer" style="height:60px" >
          <div> créé le <?php echo e($post->post_time); ?> par <?php echo e(Auth::getPrenombyId($post->post_createur)); ?> <?php echo e(Auth::getNombyId($post->post_createur)); ?> </div>
        </div>
        <?php if(Auth::isAdmin()): ?>
          <div class="col-lg-2 panel-footer pull-right">   
            <button id="supMessage" class="btn btn-success" style="margin-left:15px" data-id="<?php echo e($post->post_id); ?>">Supprimer</button> 
          </div>
        <?php else: ?>
          <div class="col-lg-2 panel-footer pull-right">   
            <button href="<?php echo e(url('forum/'.$cat.'/'.$topic[0]->topic_id.'/supMessage')); ?>" class="btn btn-success" style="margin-left:15px">Editer</button> 
            <button id="editMessage"  href="<?php echo e(url('forum/'.$cat.'/'.$topic[0]->topic_id.'/editMessage')); ?>" class="btn btn-warning" style="margin-left:15px">Supprimer</button>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <?php if($messagExist==0): ?> <!-- Si aucun message ne doit être affiché -->
    <tr><h3> Aucun message n'est sauvegardé pour ce topic '<?php echo e($topic[0]->topic_titre); ?>' </h3></tr>
  <?php endif; ?>

  <a href="<?php echo e(url('forum/'.$cat.'/')); ?>" class="btn btn-warning" style="margin-left:15px">Revenir aux catégories</a>
  <a href="<?php echo e(url('forum/'.$cat.'/'.$topic[0]->topic_id.'/newMessage')); ?>" class="btn btn-info" style="margin-left:15px">Ecrire un nouveau message </a>


  </body>
</html> 

<script>
$(function() { 
  
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  $('#supMessage').click(function() {
      $.ajax({
          url: '<?php echo e($topic[0]->topic_id); ?>/supMessage',
          type: "post",
          data: {'post_id': $(this).attr('data-id') },
          success: function(data){
            window.location.href = "";
          }
      });  
  });
});
</script>