<?php

  // Include config and library classes
  include_once('config.inc.php');
  include_once('lib/database.class.php');
  include_once('lib/login.class.php');

  // Create instances of database and login classes
  // This checks for login/logout/register requests on this page
  // It also manages sessions
  $db = new Database();
  $login = new Login($db);

  // Redirect away to the main map page if the user is logged in
  if ($login->isLoggedIn()) {
    header('Location: map.php');
  } else {

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="FileDigger allows you to upload files and share data and associate it with a physical location">
    <meta name="author" content="Tutorial Group Y11">

    <title>FileDigger - Location Based File and Data Sharing</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body>

    <?php include 'views/header.php'; ?>

    <!-- Map
    ================================================== -->
    <div id="mapCarousel" class="map"></div>
    <!-- /.map -->

    <!-- Modal Login
    ================================================== -->
    <div class="modal fade" id="loginModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Login</h4>
          </div>
          <div class="modal-body">
            <div class="panel-body" >
              <div class="panel-body" >
                <form id="loginForm" class="form-horizontal" action="index.php" method="POST">
                  <br>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="username form-control" type="text"  name="username" value="" placeholder="username">
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>                                        
                    <input class="password form-control" type="password"  name="password" placeholder="password">
                  </div>
                  <input type="hidden" name="login" value="1">                 
                </form>
              </div>
            </div>
          
          Don't have an account! <a href="#" onClick="$('#loginModal').modal('toggle'); $('#registerModal').modal('toggle');"> Sign Up Here </a>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onClick="$('#loginForm').submit();">Login</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Register
    ================================================== -->
    <div class="modal fade" id="registerModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Register</h4>
          </div>
          <div class="modal-body">
            <form id="registerForm" action="index.php" method="POST">
              <br>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>  
                <input class="email form-control" type="text" name="email" value="" placeholder="email address">
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input class="username form-control" type="text"  name="username" value="" placeholder="username">                                          
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input class="password form-control" type="password" name="password" placeholder="password">
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input class="password form-control" type="password"  name="password2" placeholder="verify password">
              </div>
                <input type="hidden" name="register" value="1">                 
            </form>
            Already have an account <a href="#" onClick="$('#loginModal').modal('toggle'); $('#registerModal').modal('toggle');"> Login Here</a>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onClick="$('#registerForm').submit();">Register</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-4">
          <img class="img-circle" src="img/circle1.png" alt="Location based downloads" style="width: 140px; height: 140px;">
          <h2>Share Files</h2>
          <p>Upload files and associate them with a location to allow them to be visible on a map to friends, or publicly to everyone.</p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" src="img/circle2.png" alt="Radius restricted downloads" style="width: 140px; height: 140px;">
          <h2>Restrict Downloading</h2>
          <p>Set files to only be viewable or downloadable in a radius of the given location. Share guides or maps only with event attendees or even create treasure hunts for friends!</p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="img-circle" src="img/circle3.png" alt="Time limited downloads" style="width: 140px; height: 140px;">
          <h2>Set Expiry</h2>
          <p>Make sure files can only be downloadable within a certain time frame. Perfect for event related files and information you only want to share for a limited period.</p>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->


      <!-- START THE FEATURETTES -->

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">Step 1. <span class="text-muted">Upload your file.</span></h2>
          <p class="lead">Choose a location and upload your file to the map. You can also choose to restrict downloading to a radius, share only with friends or set a limited period during a file will be downloadable.</p>
        </div>
        <div class="col-md-5">
          <img class="featurette-image img-responsive" src="img/picture1.png" alt="File based.">
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-5">
          <img class="featurette-image img-responsive" src="img/picture2.png" alt="Location on the map.">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Step 2. <span class="text-muted">Share the location.</span></h2>
          <p class="lead">No need to remember complex URLs to pass along. Simply tell them where on the map you put the file and they will be able to easily locate it.</p>
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">Step 3. <span class="text-muted">Download.</span></h2>
          <p class="lead">Download from the location on a mobile device if radius restriction was set, otherwise you can download from anywhere. It's that simple.</p>
        </div>
        <div class="col-md-5">
          <img class="featurette-image img-responsive" src="img/picture3.png" alt="Download.">
        </div>
      </div>

      <hr class="featurette-divider">

      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <?php include 'views/footer.php'; ?>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.19"></script>
    <script type="text/javascript" src="js/map_settings.js"></script>
    <script type="text/javascript" src="js/index_map.js"></script>
    <script type="text/javascript" src="holder.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/lib/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

<?php } ?>
