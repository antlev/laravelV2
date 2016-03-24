<?php $__env->startSection('content'); ?>
        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />


<div class="col-lg-5 col-lg-offset-5 col-md-6 col-md-offset-5">
<img id="imguser" name="myimage" width=40% src="<?php echo e(Auth::getPhotoById(Auth::id())); ?>">
<br><br>
<input id="uploadFile" placeholder="Choose File" disabled="disabled" />
<div class="fileUpload btn btn-default">
    <span>Parcourir</span>
    <input id="uploadBtn" type="file" class="upload" />
</div>


</div>
<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-2 col-lg-offset-4 col-md-2 ">

Pseudonyme : 
<div class="input-group">
<input type="text" class="form-control" id="pseudo" value="<?php echo e(ucfirst(Auth::user()->name)); ?>" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="pseudo"></i>
    </span>
    </div>

Prenom :
<div class="input-group">
<input type="text" class="form-control"  id="prenom" value="<?php echo e(ucfirst(Auth::user()->prenom)); ?>" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="prenom"></i>
    </span>
    </div>
Nom : 
<div class="input-group">
<input type="text" class="form-control" id="nom"  value="<?php echo e(ucfirst(Auth::user()->nom)); ?>" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="nom"></i>
    </span>
    </div>
Sexe : 
<div class="input-group" id="civilitenot">
<select class="form-control" id="civilite">
<option  selected="selected"><?php echo e(ucfirst(Auth::user()->civilite)); ?></option>
<?php if(Auth::user()->civilite != 'homme'): ?> <option value="homme">Homme</option> <?php endif; ?>
<?php if(Auth::user()->civilite != 'femme'): ?><option value="femme">Femme</option> <?php endif; ?>
</select>
<span class="input-group-addon">
        <i class="fa fa-save" id="civilite"></i>
    </span>
    </div>
Date de naissance :

<div class="input-group">
<input type="text" class="form-control"  id="datenaiss" value="<?php echo e(Auth::user()->datenaiss); ?>" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="datenaiss"></i>
    </span>
    </div>

Adresse : 
<div class="input-group">
<input type="text" class="form-control" id="adresse" value="<?php echo e(Auth::user()->adresse); ?>" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="adresse"></i>
    </span>
    </div>

</div>


<div class="col-lg-2 col-md-6 ">
Sport : 
<div class="input-group">

<select class="form-control" id="sport">
<?php foreach(Auth::getAllSports() as $sport): ?>  

<option value="test"><?php echo e($sport->nom_sports); ?></option>
<?php endforeach; ?>
</select>
<span class="input-group-addon">
        <i class="fa fa-save" id="sport"></i>
    </span>
</div>
Code Postal :
<div class="input-group">
<input type="text" class="form-control" id="cp" value="<?php echo e(Auth::user()->cp); ?>" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="cp"></i>
    </span>
    </div>
Ville : 
<div class="input-group">
<input type="text" class="form-control" id="ville"  value="<?php echo e(ucfirst(Auth::user()->ville)); ?>" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="ville"></i>
    </span>
    </div>
Nationalité: 
<div class="input-group">
<input type="text" class="form-control" id="nationalite" value="<?php echo e(ucfirst(Auth::user()->nationalite)); ?>" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="nationalite"></i>
    </span>
    </div>

Mobile : 
<div class="input-group">
<input type="text" class="form-control" id="mobile" value="<?php echo e(Auth::user()->mobile); ?>" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="mobile"></i>
    </span>
    </div>


Téléphone  : 
<div class="input-group">
<input type="text" class="form-control" id="telephone" value="<?php echo e(Auth::user()->telephone); ?>" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="telephone"></i>

    </span>
    </div>
    <div id="result"></div>
</div>



<style>
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
[data-notify="progressbar"] {
	margin-bottom: 0px;
	position: absolute;
	bottom: 0px;
	left: 0px;
	width: 100%;
	height: 5px;
}
</style>

<script src="<?php echo e(asset('/node_modules/socket.io-client/socket.io.js')); ?>"></script>
        
<script>
            var socket = io.connect('http://172.16.3.25:8080');
                socket.emit('test', 'Mon node js marche, bitch!');

   function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imguser').attr('src', e.target.result);
                $.ajax({
    type:"POST",
    url:"postimage",
    data:{ 'img':e.target.result},
    success: function(html){

   },
 });
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


function successNotification() { 

$.notify({
	// options
	icon: 'glyphicon glyphicon-check',
	title: 'Modification : ',
	message: 'Les données ont été sauvegardées',
	target: '_blank'
},{
	// settings
	element: 'body',
	position: null,
	type: "success",
	allow_dismiss: true,
	newest_on_top: false,
	showProgressbar: false,
	placement: {
		from: "bottom",
		align: "right"
	},
	offset: 20,
	spacing: 10,
	z_index: 10311,
	delay: 5000,
	timer: 1000,
	url_target: '_blank',
	mouse_over: null,
	animate: {
		enter: 'animated fadeInDown',
		exit: 'animated fadeOutUp'
	},
	onShow: null,
	onShown: null,
	onClose: null,
	onClosed: null,
	icon_type: 'class',
	template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
		'<span data-notify="icon"></span> ' +
		'<span data-notify="title">{1}</span> ' +
		'<span data-notify="message">{2}</span>' +
		'<div class="progress" data-notify="progressbar">' +
			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
		'</div>' +
		'<a href="{3}" target="{4}" data-notify="url"></a>' +
	'</div>' 
});

}
document.getElementById("uploadBtn").onchange = function () {
    document.getElementById("uploadFile").value = this.value;
};
	$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$("input:file").change(function (){
	var postImg = $("#image").attr("name");// image name

    var dataString = 'img='+postImg;

$.ajax({
    type:"POST",
    url:"postimage",
    data:dataString,
    success: function(html){
  
   },
 });

       
     });


$("#uploadBtn").change(function () {
        readURL(this);
    });

$("i").click(function(e){


var idfa = e.target.id;
var mod;



 $("select").each(function(){
if ($(this).attr('id') == idfa) { 
$.ajax({
      url: 'changeUserInfo',
      type: "post", 
      data: {'column': $(this).attr('id') ,'info': $('select#' + idfa).val() },
      success: function(data){
   successNotification();
      }
    });

}

});





$('input').each(function() {
     if($(this).is(':disabled') && ($(this).attr('id') == idfa)) { 
		$(this).removeAttr('disabled');
         $('i#'+idfa).toggleClass('fa-edit fa-save');
         $('i#'+idfa).css('color','green');
         mod=1;
     }
     //alert($(this).attr('id').attr('class'));
     if (!$(this).is(':disabled') && ($(this).attr('id') == idfa) && ($(this).attr('class') != 'fa-edit') && (mod!=1)) { 

       $('i#'+idfa).toggleClass('fa-save fa-edit');
         $('input#'+idfa).prop('disabled','true');
//alert($('input#' + idfa).val());
$.ajax({
      url: 'changeUserInfo',
      type: "post", 
      data: {'column': $(this).attr('id') ,'info': $('input#' + idfa).val() },
      success: function(data){
        successNotification();
      }
    });  
}
     else { //console.log($(this).attr('id') + '-' + idfa); 
 }
});
mod = 0;

});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>