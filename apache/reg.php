<?php
include_once('serverFunc/sessionHandler.php');
include_once('serverFunc/server.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/css/bootstrap.min.css" />
  <script src="https://kit.fontawesome.com/c4cafcfd34.js" crossorigin="anonymous"></script>

  <link rel="icon" type="image/png" href="assets/toy-train.png" />
  <title><?= $lang['indexTitle'] ?></title>
</head>

<body>
  <nav class="navbar navbar-expand-lg <?= $theme['navbarColor'] ?>">
    <a class="navbar-brand ms-3" href="http://localhost:8002/"><?= $lang['navbarTitle'] ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarText">
      <ul class="nav navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="http://localhost:8002/"><?= $lang['navbarHome'] ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://localhost:8002/shop.php"><?= $lang['shopTitle'] ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://localhost:8001/delivery.html"><?= $lang['navbarDelivery'] ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://localhost:8001/about.html"><?= $lang['navbarAbout'] ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://localhost:8002/filestorage.php"><?= $lang['navbarFilestorage'] ?></a>
        </li>
      </ul>
      <ul class="nav navbar-nav ml-auto me-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $lang['language'] ?>
          </a>
          <ul class="dropdown-menu <?= $theme['dropdownMenu'] ?>" aria-labelledby="navbarDarkDropdownMenuLink">
            <li><a class="dropdown-item" href='<?php echo "{$_SERVER['PHP_SELF']}?language=ru" ?>'>Русский</a></li>
            <li><a class="dropdown-item" href='<?php echo "{$_SERVER['PHP_SELF']}?language=en" ?>'>English</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $lang['theme'] ?>
          </a>
          <ul class="dropdown-menu <?= $theme['dropdownMenu'] ?>" aria-labelledby="navbarDarkDropdownMenuLink">
            <li><a class="dropdown-item" href='<?php echo "{$_SERVER['PHP_SELF']}?theme=white" ?>'><?= $lang['themeWhite'] ?></a></li>
            <li><a class="dropdown-item" href='<?php echo "{$_SERVER['PHP_SELF']}?theme=dark" ?>'><?= $lang['themeDark'] ?></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <?php
          if (isset($_SESSION['login']) and $_SESSION['login'] != session_id()) {
            echo "<a class='nav-link' href='http://localhost:8002/'><i class='fas fa-user me-1'></i>{$_SESSION['login']}</a>";
          } else {
            echo "<a class='nav-link' href='http://localhost:8002/'><i class='fas fa-user-secret me-1'></i>{$lang['guest']}</a>";
          }
          ?>
        </li>
        <?php
        if (isset($_SESSION['login']) and $_SESSION['login'] != session_id()) {
          echo "<li class='nav-item'><form method='post' action='index.php'><button type='submit' class='btn btn-primary' name='logout'>{$lang['logout']}</button></form></li>";
        }
        ?>
      </ul>
    </div>
  </nav>

  <h2 class="text-center m-1 <?= $theme['textColor'] ?>"><?= $lang['welcome'] ?></h2>
  <p class="lead text-center <?= $theme['textColor'] ?>"><?= $lang['description'] ?></p>
  <div class="container row mw-100">
    <div class="f1 col d-flex flex-row justify-content-center">
      <form method="post" action="index.php" class="border p-3">
        <?php include('serverFunc/errorsLog.php'); ?>
        <div class="">
          <label for="login" class="form-label <?= $theme['textColor'] ?>"><?= $lang['loginLabel'] ?></label>
          <input type="text" class="form-control" id="login" name="loginLog" aria-describedby="loginHelp" />
          <div id="loginHelp" class="form-text"><?= $lang['enterLogin'] ?></div>
        </div>
        <div class="">
          <label for="passwordInput" class="form-label <?= $theme['textColor'] ?>"><?= $lang['password'] ?></label>
          <input type="password" class="form-control" id="passwordInput" name="passwordLog" />
          <div id="passwordHelp" class="form-text"><?= $lang['enterPassword'] ?></div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary mt-2" name="loginUser"><?= $lang['login'] ?></button>
        </div>
      </form>
    </div>
    <div class="f2 col d-flex flex-row-reverse justify-content-center">
      <form method="post" action="index.php" class="border p-3">
        <?php include('serverFunc/errorsReg.php'); ?>
        <div class="">
          <label for="login" class="form-label <?= $theme['textColor'] ?>"><?= $lang['loginLabel'] ?></label>
          <input type="text" class="form-control" id="login" name="loginReg" aria-describedby="loginHelp" />
          <div id="loginHelp" class="form-text"><?= $lang['enterLogin'] ?></div>
        </div>
        <div class="">
          <label for="passwordInput" class="form-label <?= $theme['textColor'] ?>"><?= $lang['password'] ?></label>
          <input type="password" class="form-control" id="passwordInput" name="passwordReg" />
          <div id="passwordHelp" class="form-text"><?= $lang['enterPassword'] ?></div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary mt-2" name="regUser"><?= $lang['register'] ?></button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>