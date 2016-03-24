<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />




    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>