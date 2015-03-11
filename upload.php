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

  // Redirect away if user is not logged in
  if (!$login->isLoggedIn()) {
    header('Location: login.php');
  } else {
    
    // Use the files class here
    $files = new Files($db);
    
    // Along with POST details from form:
    // POSITION VARS
    if (isset($_POST['latitude']) && isset($_POST['latitude'])) {
      $userid = $_SESSION['userID'];
      $lat = $_POST['latitude'];
      $lng = $_POST['longitude'];
    
      // To add the file to the DB and move to storage
      if ($result = $files->uploadFile($userid, $lat, $lng) == false) {
        echo $files->getError();
      } else {
        echo $result;
      }
    } else {
      echo 'Required lat/long not given';
    }
  }

?>
