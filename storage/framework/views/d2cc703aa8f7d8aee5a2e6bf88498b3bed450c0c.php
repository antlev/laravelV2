<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>M2L</title>
    <link href="<?php echo e(('/laravel/public/css/bootstrap-datetimepicker.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(('/laravel/public/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(('/laravel/public/css/bootstrap-theme.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(('/laravel/public/css/bootstrap-responsive.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(('/laravel/public/css/jquery.datetimepicker.css')); ?>" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>



    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <?php /* <link href="<?php echo e(elixir('css/app.css')); ?>" rel="stylesheet"> */ ?>

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="<?php echo e(url('/home')); ?>">
                    M2L
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
               <!--  <ul class="nav navbar-nav">
                    <li><a href="<?php echo e(url('/home')); ?>">Accueil</a></li>
                </ul> -->
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo e(url('users')); ?>">Formulaire joueur</a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo e(url('calendar')); ?>">Calendrier</a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo e(url('list_view/lieux')); ?>">Lieux</a></li>
                </ul>
                <?php if(Auth::isAdmin()): ?>
                     <ul class="nav navbar-nav">
                        <li><a href="<?php echo e(url('list_view/planning')); ?>">Plannification</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo e(url('list_view/participants')); ?>">Participants</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo e(url('list_view/roles')); ?>">Roles</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo e(url('list_view/user_roles')); ?>">Roles Utilsateurs</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo e(url('list_view/users')); ?>">Utilisateurs</a></li>
                    </ul>
                    <!-- <ul class="nav navbar-nav btn-group">
                            <button class="btn" data-toggle="dropdown">Historique</button>
                            <span class="caret"></span>
                        <ul class="dropdown-menu">
                          <li><a href="<?php echo e(url('suivi_mot_passe')); ?>">Suivi mot de passe</a></li>
                          <li><a href="<?php echo e(url('suivi_reserve')); ?>">Suivi réservation de salle</a></li>
                          <li><a href="<?php echo e(url('Histo_stat')); ?>">Historique Statistique</a></li>
                        </ul>
                    </ul> -->
                <?php endif; ?>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <?php if(Auth::guest()): ?>
                        <li><a href="<?php echo e(url('/login')); ?>">Connexion</a></li>
                        <li><a href="<?php echo e(url('/register')); ?>">Enregistrement</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/logout')); ?>"><i class="fa fa-btn fa-sign-out"></i>Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>"></script>
    <?php if(!isset($table)): ?>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <?php endif; ?>
    <script src="<?php echo e(('/laravel/public/js/moment-with-locales.js')); ?>"></script>
            <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>

    <script src="<?php echo e(('/laravel/public/js/boostrap-datetimepicker.js')); ?>"></script>
        <script src="<?php echo e(asset('js/bootstrap-notify.js')); ?>"></script>

    <?php echo $__env->yieldContent('profil'); ?>

    <!-- JavaScripts -->
    <!-- // <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
    <?php /* <script src="<?php echo e(elixir('js/app.js')); ?>"></script> */ ?>
</body>
</html>
