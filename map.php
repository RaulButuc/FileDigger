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

  // Redirect away to dedicated login page if user is not logged in
  if (!$login->isLoggedIn()) {
    header('Location: login.php');
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

    <!-- Styles specific for this page -->
    <style type="text/css">
      #fullScreenMap {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
      }

      #htmlParsingPlaceholder {
        display: none;
      }
      .controls {
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        margin: auto;
        margin-top: 80px;
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
        right: 0;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
    </style>
  </head>

  <body>

    <!-- NAVBAR
    ================================================== -->
    <?php include 'views/header.php'; ?>

    <!-- Map
    ================================================== -->
    <input id="pac-input" class="controls" type="text" placeholder="Search for a place" <?php if (is_numeric($_GET['lat']) && is_numeric($_GET['long'])) { echo 'value="'.$_GET['lat'].', '.$_GET['long'].'"'; } ?>>
    <div id="fullScreenMap" class="carousel slide" data-ride="carousel"></div>
    <!-- /.map -->

    <div class="container">

      <!-- FOOTER -->
      <?php include 'views/footer.php'; ?>

    </div><!-- /.container -->

   <!-- Getting all the files
    ================================================== -->
    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="holder.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.19&libraries=places"></script>
    <script type="text/javascript" src="js/map_settings.js"></script>
    <script type="text/javascript" src="js/main_map.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/lib/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php
  } // content is only shown to logged in users
?>
