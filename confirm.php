<?php

  // Include config and library classes
  include_once('lib/database.class.php');
  include_once('lib/login.class.php');
  include_once('lib/files.class.php');

  // Create instances of database and login classes
  // This checks for login/logout/register requests on this page
  // It also manages sessions
  $db = new Database();
  $login = new Login($db);

  // Redirect away to map if the account is already logged in
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

    <title>Confirm your Account - FileDigger</title>

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

  <body>

    <!-- NAVBAR
    ================================================== -->
    <?php include 'views/header.php'; ?>

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
                    <input class="username form-control" type="text"  name="username" value="" placeholder="username or email">
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

    <div class="container">
        
          <h1 class="page-header">Confirm Account</h1>

          <div <?php if (!isset($_GET['err'])) { echo 'style="display:none"'; } ?> class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
          <div <?php if (!isset($_GET['success'])) { echo 'style="display:none"'; } ?> class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
          
          <p>Thank you for registering with FileDigger!</p>
          <p>You must confirm your email to complete the registration process. We have sent you a link with a unique URL to activate your account</p>
          <br><br>

      <!-- FOOTER -->
      <?php include 'views/footer.php'; ?>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="holder.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/lib/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php
  } // content is only shown to non-logged in users
?>