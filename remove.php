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
    
    // Check that a file ID was given
    if (isset($_GET['fileID'])) {
      // Attempt to delete the file and redirect for success or failure
      if ($files->removeFile($_GET['fileID'], $_SESSION['userID'])) {
        header('Location: account.php?success='.urlencode($_GET['fileID']).'&a='.$action);
      } else {
        header('Location: account.php?err='.urlencode($files->getError()));
      }
    } else {
      // If not file was given, error redirect
      header('Location: account.php?err='.urlencode('An error occured when trying to delete a file'));
    }
    
  }

?>
