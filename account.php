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

    <title> Your Files - FileDigger</title>

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
          
          <div <?php if (!isset($_GET['err'])) { echo 'style="display:none"'; } ?> class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
          <div <?php if (!isset($_GET['success'])) { echo 'style="display:none"'; } ?> class="alert alert-success">The file with ID <?php echo htmlspecialchars($_GET['success']); ?> has been deleted</div>
          
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
            <input id="search" class="username form-control" type="text"  name="Search" value="" placeholder="Search for your files">
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr id="tableHeader">
                  <th>ID</th>
                  <th>Filename</th>
                  <th>Latitude</th>
                  <th>Longitude</th>
                  <th>Radius</th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              
              <?php
                // New instance of files class to use functions
                $files = new Files($db);
                
                // Get all the files from the current user
                if ($userFiles = $files->getFilesByUser($_SESSION['userID'])) {
                
                  // Loop through the user's files and add them to the table
                  foreach($userFiles as $currentFile) {
                      // Set radius to string 'None' if it is 0
                      $currentRadius = ($currentFile['Radius'] == 0 ? 'None' : $currentFile['Radius']);
                      echo '<tr>';
                      echo '<td>' . $currentFile['ID'] . '</td>';
                      echo '<td class="name">' . $currentFile['Name'] . '</td>';
                      echo '<td>' . $currentFile['Latitude'] . '</td>';
                      echo '<td>' . $currentFile['Longitude'] . '</td>';
                      echo '<td>' . $currentRadius  . '</td>';
                      echo '<td><a href="download.php?fileID=' . $currentFile['ID'] . '" class="btn btn-success" role="button">Download</a></td>'; 
                      echo '<td><a href="map.php?lat=' . $currentFile['Latitude'] . '&long=' . $currentFile['Longitude'] . '" class="btn btn-info" role="button">View on Map</a></td>';
                      echo '<td><a href="remove.php?fileID=' . $currentFile['ID'] . '" class="btn btn-danger" role="button">Remove</a></td>';
                      echo '</tr>';
                  }
                  
                } else {
                  echo '<tr><td colspan="6">You have not uploaded any files yet! <a href="map.php">Get started at the map</a></td></tr>';
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
    <script src="js/search.js"></script>
    <script src="holder.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/lib/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php
  } // content is only shown to logged in users
?>
