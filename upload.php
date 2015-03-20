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

  // Redirect away if user is not logged in
  if (!$login->isLoggedIn()) {
    header('Location: login.php');
  } else {
    
    // Use the files class here
    $files = new Files($db);
    
    // Along with POST details from form:
    // POSITION VARS
    if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['radius'])) {
      $userid = $_SESSION['userID'];
      $lat = $_POST['latitude'];
      $lng = $_POST['longitude'];
      $radius = $_POST['radius'];
    
      // To add thea file to the DB and move to storage
      if ($result = $files->uploadFile($userid, $lat, $lng, $radius) == false) {
        header('Location: account.php?err='.urlencode($files->getError()));
      } else {
        // Redirect to the files list if this succeeded
        header('Location: account.php');
      }
    } else {
      header('Location: account.php?err='.urlencode('Required latitude/longitude not given!'));
    }
  }

?>
