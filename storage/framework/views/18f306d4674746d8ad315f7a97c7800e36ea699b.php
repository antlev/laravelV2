 <?php
 


 ?>

 <style>
 body {
  padding-top: 70px;
}
footer {
  padding-left: 15px;
  padding-right: 15px;
}

/*
 * Off Canvas
 * --------------------------------------------------
 */
@media  screen and (max-width: 768px) {
  .row-offcanvas {
    position: relative;
    -webkit-transition: all 0.4s ease-out;
    -moz-transition: all 0.4s ease-out;
    transition: all 0.4s ease-out;
  }

  .row-offcanvas-left
  #sidebarLeft {
    left: -40%;
  }

  .row-offcanvas-left.active {
    left: 40%;
  }
 
  .row-offcanvas-right 
  #sidebarRight {
    right: -40%;
  }

  .row-offcanvas-right.active {
    right: 40%;
  }

  .sidebar-offcanvas {
    position: absolute;
    top: 0;
    width: 40%;
    margin-left: 10px;
  }
}
 
 </style>
 <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.css')); ?>">

 <script src="<?php echo e(asset('js/chris.js')); ?>"></script>

 <script src="<?php echo e(asset('js/jquery-2.1.1.min.js')); ?>"></script>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
 <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle" type="button" data-target=".navbar-collapse" data-toggle="collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="http://172.16.3.25/laravel/public/index.php/forum/">Forum</a></li>          
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-left">
          <div class="row-offcanvas row-offcanvas-right">
             <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebarLeft" role="navigation">
               <p class="visible-xs">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
              </p>
              <div class="well sidebar-nav">
                <ul class="nav">
                <li> ShoutBox</li>
 <!--  <textarea class="form-control" rows="15" cols="15" id="aboutDescription" style="resize: none;" disabled> -->

<?php echo e(SQLCommand::get_shouts()); ?>



 <!--  </textarea> -->
  <br>
               <input class="form-control" type="text" placeholder="Votre Pseudonyme">
               <input class="form-control" type="text" placeholder="Votre Message">
               <br>
             <a class="btn btn-primary" href="#">
  <i class="fa fa-send" id="sendmsg"></i> Envoyer</a>

                </ul>
              </div><!--/.well -->
			  <div class="well sidebar-nav">
                <ul class="nav">
                  <li>News</li>
                  <li class="active"><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                </ul>
              </div><!--/.well -->
			  
            </div><!--/span-->
            
            <div class="col-xs-12 col-sm-6">
              <p class="pull-left visible-xs">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
              </p>
              <p class="pull-right visible-xs">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvasright"><i class="glyphicon glyphicon-chevron-right"></i></button>
              </p>
              
                
              <div class="row">
                <div id="content">ezaeazeazeazezezaezeza</div>
              </div><!--/row-->
            </div><!--/span-->
    
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebarRight" role="navigation">
               <p class="visible-xs">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
              </p>
              <div class="well sidebar-nav">
                <ul class="nav">
                  <li>Classement</li>
                  <li class="active"><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                </ul>
              </div><!--/.well -->
			  <div class="well sidebar-nav">
                <ul class="nav">
                  <li>ShoutBox</li>
                  <li class="active"><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                  <li><a href="#">Link</a></li>
                </ul>
              </div><!--/.well -->
            </div><!--/span-->
          
        </div>

      </div><!--/row-->
        
      <hr>
        
      <footer>
        <p>© Company 2013</p>
      </footer>
 
    </div><!--/.container-->


<link href="pusher-realtime-chat-widget/src/pusher-chat.css" rel="stylesheet" />


<script>
$( document ).ready(function() {

$('#sendmsg').click(function() { 
alert('ok');
})

});


</script>