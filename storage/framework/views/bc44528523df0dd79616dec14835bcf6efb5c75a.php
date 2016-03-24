
<meta http-equiv="content-type" content="text/html; charset=utf-8" />


<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/login.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">

<script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap-notify.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/login/functions.js')); ?>"></script>
<script src="<?php echo e(asset('js/login/checkbox.js')); ?>"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
                       <div class="wrap">
                <p class="form-title">
                    Ligue des Lorraines</p>
<br>
<br>

<br>
<?php echo Form::open(array('url'=>'/login','method'=>'POST', 'id'=>'myform')); ?>

    <?php echo csrf_field(); ?>

    <div>
        Email
   <?php echo Form::text('email',old('email'),array('id'=>'','class'=>'form-control span6','placeholder' => 'Email' )); ?>    
</div>

    <div>
        Password
  <?php echo Form::password('password',array('class'=>'form-control span6', 'placeholder' => 'Please Enter your Password'  )); ?>    
</div>
<div class="remember-forgot">
                    <div class="row">
                        <div class="col-md-6">
    <div>
        
    </div>
</div></div></div>
<br>
<?php echo Form::submit('Se connecter', array('class'=>'btn btn-success send-btn','style'=>'width:100% !important')); ?>

<div>&nbsp;</div>
 <span class="button-checkbox">
<button type="button" class="btn btn-default">Se rappeler</button>
<input type="checkbox" class="hidden" name="remember" />
    </span>
|     
<button class="btn btn-default"><i class="fa fa-sign-in" style="cursor:pointer"></i> Inscription</button>
<?php echo Form::close(); ?>



<?php if(strstr($errors->default,'These credentials do not match our records')): ?>  
<script>

errorLogin();
</script>

<?php endif; ?>
 
