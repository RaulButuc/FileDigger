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
    // Ensure the file ID was given of the file to be downloaded
    if (isset($_POST['fileID']) || isset($_GET['fileID'])) {

      // Use POST or GET variables, either will work
      if (isset($_POST['fileID'])) {
        $fileID = $_POST['fileID'];
      } else {
        $fileID = $_GET['fileID'];
      }

      // Get the file location using files class
      $files = new Files($db);

      // Check that the file ID exists
      if ($fileInfo = $files->getFile($fileID)) {

        // Force this file to be downloaded and not viewed on the server
        header("Content-Description: File Transfer"); 
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary"); 

        // Return the file nmame in the headers as the stored file name (stripped from location)
        header("Content-Disposition: attachment; filename=\"" . substr($fileInfo['Location'], strrpos($fileInfo['Location'], '/') + 1) . "\"");

        // Read the file from its location and send it to the client
        readfile($fileInfo['Location']);

      // Redirect back to map page if the file couldnt be downloaded
      } else {
        header('Location: map.php');
      }
    } else {
      header('Location: map.php');
    }
  }

?>
