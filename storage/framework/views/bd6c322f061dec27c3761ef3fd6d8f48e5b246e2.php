<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>" rel="stylesheet"> </script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>" rel="stylesheet"> </script>
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>


<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
      <img src="<?php echo e(asset('img/logo.png')); ?>" style="width:8%">
    <h1 onclick="location.href='<?php echo e(url('forum')); ?>'" >Forum De La Maison Des Ligues</h1>
  </div>
</div>

<!-- Menu de navigation -->
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle pull-right" type="button" data-toggle="dropdown" style="margin-right:20px">Navigation Forum
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li class="dropdown-header" ><a style="font-weight:bold" href="<?php echo e(url('forum')); ?>">Index</a></li>
    <?php foreach($categories as $cats): ?>  <!-- On affiche les sous_catégories -->
      <li class="dropdown-header"><a href="<?php echo e(url('forum/'.$cats->cat_id.'/')); ?>"><?php echo e($cats->cat_nom); ?></a></li>
      <?php foreach($topic as $topic_as): ?> <!-- On affiche les catégories -->
        <?php if($topic_as->topic_cat==$cats->cat_id): ?>
          <li><a href="<?php echo e(url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)); ?>"><?php echo e($topic_as->topic_titre); ?></a></li>
        <?php endif; ?>
      <?php endforeach; ?>
      </li>
    <?php endforeach; ?>
  <ul>
</div>


    <div class="clearfix"></div>
  </div>

  <table class="table forum table-striped">
    <thead>
      <tr>
        <a href="<?php echo e(url('forum/'.$cat.'/newTopic')); ?>" class="fa fa-pencil-square-o" style="font-size:25px;color:black !important;margin-left:30px"></a>
        <a href="<?php echo e(url('forum/'.$cat.'/newTopic')); ?>" class="btn btn-info" style="margin-left:15px">Créer un Nouveau Topic </a>
        <?php if(!empty($topic)): ?>
        <th class="cell-stat"><h3><?php echo e($catName); ?></h3></th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
        <?php endif; ?>      
      </tr>
    </thead>


    <tbody>
      <?php if(empty($topic)): ?>
        <tr><h3> Aucun topics n'a été créé dans la catégorie <?php echo e($catName); ?> </h3></tr>
      <?php else: ?>

      <?php foreach($topic as $topic_as): ?> <!-- On affiche les catégories -->
      <tr>
        <td>
          <h4><a href="<?php echo e(url('forum/'.$cat.'/'.$topic_as->topic_id)); ?>"><?php echo e($topic_as->topic_titre); ?></a></h4>
        </td>
 

        <td class="cell-stat text-center hidden-xs hidden-sm">post: 0</td>
        <td class="cell-stat hidden-xs hidden-sm">last_post: 0</td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>

    </tbody>
  </table>


  </body>

  <footer>
    <i class="fa fa-facebook-5x"></i>
    <a href="<?php echo e(url('forum')); ?>" class="btn btn-warning">Revenir à l'index </a>
    <h2> [[Footer]]</h2>
  </footer>
</html> 

