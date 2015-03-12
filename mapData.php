<?php 
  include_once('config.inc.php');
  include_once('lib/database.class.php');
  include_once('lib/login.class.php');
  include_once('lib/files.class.php');

  $db = new Database();
  $login = new Login($db);

  if (!$login->isLoggedIn()) {
    header('Location: login.php');
  } else {
    $files = new Files($db);
    if ($fileInfo = $files->getAllFiles()) {
      echo json_encode($fileInfo);
    }
  }
?>