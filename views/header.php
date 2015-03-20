<div class="navbar-wrapper">
  <div class="container">

    <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">
            <img style="max-height:40px;margin-right:15px;margin-top:-10px;float:left;" src="img/logo.png">
            <p style="float:right;">FileDigger</p>
          </a>
        </div>
        <?php if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'confirm.php') { ?>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#" data-toggle="modal" data-target="#registerModal"><span class="glyphicon glyphicon-user"></span> Register</a></li>
            <li><a href="#" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          </ul>
        </div>
        <?php } else if (basename($_SERVER['PHP_SELF']) == 'map.php' || basename($_SERVER['PHP_SELF']) == 'account.php'|| basename($_SERVER['PHP_SELF']) == 'user.php') { ?>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="user.php"><?php echo $_SESSION['username']; ?></a></li>
            <li><a href="account.php">My Files</a></li>
            <li><a href="?logout"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
          </ul>
        </div>
        <?php } ?>
      </div>
    </nav>

  </div>
</div>
