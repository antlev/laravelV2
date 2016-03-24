<?php $__env->startSection('content'); ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#vue_list').DataTable({
            	"language":{
            		"url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            	}
            });
        });
    </script>

<body>
    <div class="container">

        <table id="vue_list" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <?php foreach($names as $nom): ?>
                        <th><?php echo e(trans("view.$nom")); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($types as $type): ?>
                <tr>
                    <?php foreach($names as $value): ?>
                    <td><?php echo e($type->$value); ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <script type="text/javascript">
        $('#vue_list')
            .removeClass('display')
            .addClass('table table-striped table-bordered');
    </script>
</body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>