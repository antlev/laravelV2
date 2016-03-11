<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <link href="{{('/laravel/public/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/forum.css')}}">
    <script src="{{ asset('js/jquery-2.1.1.min.js')}}"></script>

        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>



<div class="container" style="margin-top: 35px">
  <div class="page-header page-heading">
    <img src="{{asset('img/logo.png')}}" style="width:8%">
    <h1 href="{{url('forum')}}" >Forum De La Maison Des Ligues</h1>
  </div>
  <ol class="breadcrumb pull-right where-am-i">
    <li><a href="#">Forums</a></li>
    <li class="active">List of topics</li>
  </ol>
  <div class="clearfix"></div>

<h4 style="margin-left:15px">Message : </h4>

  <table class="table forum table-striped">
    <thead>
      <tr>
        <th class="cell-stat"></th>
        <input type="text" id="msgtosend" class="toto form-control">
      </tr>
    </thead>
  </table>
    
      <tbody>

      </tbody></br>
      <input type="hidden" id="savetopic" value="{{ $topic }}">
          <a href="{{url('forum/'.$cat.'/'.$topic)}}" class="btn btn-warning" style="margin-left:15px"> Revenir au topic </a>
          <button class="btn btn-info" style="margin-left:15px" id="savemsg"> Sauvegarder votre message </button>
</div>

</html> 

<script>
$(function() { 
  
  //$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$('#savemsg').click(function() {

$.ajax({
      url: {{$cat}}/{{$topic}}/savemsg,
      type: "post",
      data: {'msg': $('#msgtosend').val(),'topic':$('#savetopic').val() },
      success: function(data){
    alert('ok');
      }
    });  
});

});
</script>