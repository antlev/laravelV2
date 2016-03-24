@extends('layouts.app')

@section('content')
        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
<div class="col-lg-12 col-lg-offset-5">
<img id="imguser" name="myimage" style="width:10% !important" src="{{ Auth::getPhotoById($userinfo[0]['id']) }}">
<br>
<br>
<b>Pseudonyme</b> : {{$userinfo[0]['name']}}<br>
<b>Nom</b> : {{$userinfo[0]['nom']}}<br>
<b>Prénom</b> : {{$userinfo[0]['prenom']}}<br>
<b>Age</b> : <?php 
$from = new DateTime($userinfo[0]['datenaiss']);
$to   = new DateTime('today');

?>
{{$from->diff($to)->y}}<br>
<b>Ville</b> : {{$userinfo[0]['ville']}}<br>
<b>Nationalité</b> : {{$userinfo[0]['nationalite']}}<br>
<br>
<b>Sport</b> : {{$userinfo[0]['sport']}}<br>
<b>Niveau</b> : 
@if($userinfo[0]['classement']!="") {{$userinfo[0]['classement']}} 
@else {{'N/A'}} 
@endif<br>
<div class="col-lg-12">&nbsp;</div>
<button class="btn btn-default" onclick="window.location='{{ url("members") }}'"><i class="fa fa-arrow-circle-left"></i> Revenir en arrière</button>

</div>
<br>

@endsection

