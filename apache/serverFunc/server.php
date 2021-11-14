<?php
include_once("database/database.php");

function showRegErrors()
{
  if (!empty($GLOBALS['errorsReg'])) {
    if (count($GLOBALS['errorsReg']) > 0) {
      echo "<div class='error'>";
      foreach ($GLOBALS['errorsReg'] as $error) {
        echo "<p class='text-dark'>{$error}</p>";
      }
      echo "</div>";
    }
  }
}

function showLogErrors()
{
  if (!empty($GLOBALS['errorsLog'])) {
    if (count($GLOBALS['errorsLog']) > 0) {
      echo "<div class='error'>";
      foreach ($GLOBALS['errorsLog'] as $error) {
        echo "<p class='text-dark'>{$error}</p>";
      }
      echo "</div>";
    }
  }
}

function isRegErrors()
{
  if (!empty($GLOBALS['errorsReg'])) {
    return 1;
  } else {
    return 0;
  }
}

function isLogErrors()
{
  if (!empty($GLOBALS['errorsLog'])) {
    return 1;
  } else {
    return 0;
  }
}

$login = "";
$errorsReg = array();
$errorsLog = array();

$db = new Database();

if (isset($_POST['regUser'])) {
  $login = $db->real_escape_string($_POST['loginReg']);
  $password = $db->real_escape_string($_POST['passwordReg']);

  if (empty($login)) {
    array_push($errorsReg, "Требуется ввести имя учетной записи!");
  }
  if (empty($password)) {
    array_push($errorsReg, "Требуется ввести пароль учетной записи!");
  }

  $user = $db->checkDataUsers($login);

  if ($user) {
    if ($user['login'] === $login) {
      array_push($errorsReg, "Введенное имя уже существует!");
    }
  }

  if (count($errorsReg) == 0) {
    $db->setPasswordUser($login, $password);
    $db->generateAPIKey($login);
    $_SESSION['login'] = $login;
    $_SESSION['success'] = "Вы успешно вошли!";
  }
}

if (isset($_POST['loginUser'])) {
  $login = $db->real_escape_string($_POST['loginLog']);
  $password = $db->real_escape_string($_POST['passwordLog']);

  if (empty($login)) {
    array_push($errorsLog, "Требуется ввести имя учетной записи!");
  }
  if (empty($password)) {
    array_push($errorsLog, "Требуется ввести пароль учетной записи!");
  }

  if (count($errorsLog) == 0) {
    $results = $db->getUserByLoginPassword($login, $password);
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['login'] = $login;
      $_SESSION['success'] = "Вы успешно вошли!";
    } else {
      array_push($errorsLog, "Имя или пароль пользователя неверны!");
    }
  }
}

if (isset($_POST['logout'])) {
  session_destroy();
  unset($_SESSION['login']);
}
