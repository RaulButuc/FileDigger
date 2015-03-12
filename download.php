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
    if (isset($_POST['file_location'])) {
      $file = $_POST['file_location'];

      header("Content-Description: File Transfer"); 
      header("Content-Type: application/octet-stream");
      header("Content-Transfer-Encoding: Binary"); 
      header("Content-Disposition: attachment; filename=\"$file\"");

      readfile ($file); 
    }
  }

?>
