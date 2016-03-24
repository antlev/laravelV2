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
      <img src="<?php echo e(asset('img/logo.png')); ?>" style="width:8%">
      <h1 onclick="location.href='<?php echo e(url('forum')); ?>'" >Forum De La Maison Des Ligues</h1>
    <p class="lead">Bienvenue sur le forum de la maison des ligues</p>
  </div>
</div>

    <!-- Menu de navigation -->
    <ol class="breadcrumb pull-right where-am-i">
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
    </ol>

  <table class="table forum table-striped">
    <thead>
      <?php foreach($forum as $forum_as): ?> <!-- On affiche les catégories -->
      <tr>
        <th class="cell-stat"></th>
        <th>
          <h3><?php echo e($forum_as->forum_name); ?><br><small><?php echo e($forum_as->forum_desc); ?></small></h3>
        </th> 
        <th class="cell-stat text-center hidden-xs hidden-sm">Topics</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
      </tr>
    </thead>


    
      <tbody>
        <?php foreach($categories as $cat): ?>  <!-- On affiche les sous_catégories -->
          <?php if($forum_as->forum_id == $cat->forum_id): ?> <!-- Uniquement si elles appartiennent à la catégorie affichée -->
          <tr>
            <td class="text-center"><i class="fa fa-heart fa-2x text-primary"></i></td>
            <td>
               <h4><a href="<?php echo e(url('forum/'.$cat->cat_id)); ?>"><?php echo e($cat->cat_nom); ?></a><br><small><?php echo e($cat->cat_desc); ?></small></h4> <!-- href renvoie l'id de la categorie au controleur si l'on clique dessus -->
            </td>
            <td class="text-center hidden-xs hidden-sm"><a href="#"></a></td>
            <td class="text-center hidden-xs hidden-sm"><a href="#">152123</a></td>
            <td class="hidden-xs hidden-sm">by <a href="#">Jane Doe</a><br><small><i class="fa fa-clock-o"></i> 3 months ago</small></td>
          </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    <?php endforeach; ?>

  </body>
</html> 

