<?php

  // Include library classes
  include_once('lib/database.class.php');
  include_once('lib/login.class.php');

  // Create instances of database and login classes
  // This checks for login/logout/register requests on this page
  // It also manages sessions
  $db = new Database();
  $login = new Login($db);

  // Redirect away to dedicated login page if user is not logged in
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

    <title>FileDigger - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Styles specific for this page -->
    <style type="text/css">
      #loginRegisterForms {
        padding: 30px;
        margin: 0 auto;
        margin-top: 20%;
      }
    </style>
  </head>
<!-- NAVBAR
================================================== -->
  <body>

    <?php include 'views/header.php'; ?>

    <div class="container">

      <div id="loginRegisterForms">

        <!-- LOGIN FORM
        ================================================== -->
        <div class="col-md-6">
          <form id="loginForm" class="form-signin" action="index.php" method="POST">
            <h2 class="form-signin-heading">Sign in to continue:</h2>
            <div <?php if (!isset($_GET['err']) || $_GET['a'] != 'login') { echo 'style="display:none"'; } ?> id="login-alert" class="alert alert-danger col-sm-12"><?php echo htmlspecialchars($_GET['err']); ?></div>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input class="username form-control" type="text"  name="username" value="" placeholder="username">
            </div>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>                                        
              <input class="password form-control" type="password"  name="password" placeholder="password">
            </div>
            <input type="hidden" name="login" value="1">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>         
          </form>
        </div>
        <!-- /.login -->

        <!-- REGISTER FORM
        ================================================== -->
        <div class="col-md-6">
              <form id="registerForm" action="index.php" method="POST">
                <h2 class="form-signin-heading">Or create a new account:</h2>
                <div <?php if (!isset($_GET['err']) || $_GET['a'] != 'register') { echo 'style="display:none"'; } ?> id="login-alert" class="alert alert-danger col-sm-12"><?php echo htmlspecialchars($_GET['err']); ?></div>
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
                  <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>                 
              </form>
        </div>
        <!-- /.register -->

      </div>

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
  } // content is only shown to not logged in users
?>
