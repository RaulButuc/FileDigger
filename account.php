<?php

  // Include config and library classes
  include_once('config.inc.php');
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

    <title> Account - FileDigger</title>

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

   

    <div class="container">
        
          <h1 class="page-header">Your Files</h1>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Filename</th>
                  <th>Latitude</th>
                  <th>Longitude</th>
                  <th>Radius</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              
              <?php
                // New instance of files class to use functions
                $files = new Files($db);
                
                // Get all the files from the current user
                $userFiles = $files->getFilesByUser($_SESSION['userID']);
                
                // Loop through the user's files and add them to the table
                foreach($userFiles as $currentFile) {
                    // Set radius to string 'None' if it is 0
                    $currentRadius = ($currentFile['Radius'] == 0 ? 'None' : $currentFile['Radius']);
                    echo '<tr>';
                    echo '<td>' . $currentFile['ID'] . '</td>';
                    echo '<td>' . $currentFile['Name'] . '</td>';
                    echo '<td>' . $currentFile['Latitude'] . '</td>';
                    echo '<td>' . $currentFile['Longitude'] . '</td>';
                    echo '<td>' . $currentRadius  . '</td>';
                    echo '<td><a href="remove.php?fileID=' . $currentFile['ID'] . '" class="btn btn-danger" role="button">Remove</a></td>'; 
                    echo '</tr>';
                }
                ?>
              </tbody>
            </table>
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.19"></script>
    <script type="text/javascript" src="js/main_map.js"></script>
    <script type="text/javascript" src="js/map_settings.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/lib/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php
  } // content is only shown to logged in users
?>
