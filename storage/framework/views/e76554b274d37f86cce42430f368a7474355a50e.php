<?php $__env->startSection('content'); ?>
        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
<div class="col-lg-12 col-lg-offset-5">
<img id="imguser" name="myimage" style="width:10% !important" src="<?php echo e(Auth::getPhotoById($userinfo[0]['id'])); ?>">
<br>
<br>
<b>Pseudonyme</b> : <?php echo e($userinfo[0]['name']); ?><br>
<b>Nom</b> : <?php echo e($userinfo[0]['nom']); ?><br>
<b>Prénom</b> : <?php echo e($userinfo[0]['prenom']); ?><br>
<b>Age</b> : <?php 
$from = new DateTime($userinfo[0]['datenaiss']);
$to   = new DateTime('today');

?>
<?php echo e($from->diff($to)->y); ?><br>
<b>Ville</b> : <?php echo e($userinfo[0]['ville']); ?><br>
<b>Nationalité</b> : <?php echo e($userinfo[0]['nationalite']); ?><br>
<br>
<b>Sport</b> : <?php echo e($userinfo[0]['sport']); ?><br>
<b>Niveau</b> : 
<?php if($userinfo[0]['classement']!=""): ?> <?php echo e($userinfo[0]['classement']); ?> 
<?php else: ?> <?php echo e('N/A'); ?> 
<?php endif; ?><br>
<div class="col-lg-12">&nbsp;</div>
<button class="btn btn-default" onclick="window.location='<?php echo e(url("members")); ?>'"><i class="fa fa-arrow-circle-left"></i> Revenir en arrière</button>

</div>
<br>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>