<?php
include_once('serverFunc/sessionHandler.php');
include_once('serverFunc/server.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/css/bootstrap.min.css" />

    <script src="https://kit.fontawesome.com/c4cafcfd34.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="src/index.css">
    <script src="src/index.js"></script>

    <link rel="icon" href="assets/tictactoe.svg">
    <title>Крестики-нолики</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand ms-3" href="http://localhost:8000/">Крестики-нолики</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarText">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="http://localhost:8000/">Играть</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/rules.php">Правила игры</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto me-3">
                <li class="nav-item">
                    <?php
                    if (isset($_SESSION['login'])) {
                        echo "<a class='nav-link' href='http://localhost:8000/'><i class='fas fa-user me-1'></i>{$_SESSION['login']}</a>";
                    } else {
                        echo "<a class='nav-link' href='#'><i class='fas fa-user-secret me-1'></i>Гость</a>";
                    }
                    ?>
                </li>
                <?php
                if (isset($_SESSION['login'])) {
                    echo "<li class='nav-item'><form method='post' action='index.php'><button type='submit' class='btn btn-secondary' name='logout'>Выйти</button></form></li>";
                } else {
                    echo "<li class='nav-item'><a class='nav-link' data-bs-toggle='modal' data-bs-target='#loginModal' href='#'>Войти</a></li>";
                    echo "<li class='nav-item'><a class='nav-link' data-bs-toggle='modal' data-bs-target='#regModal' href='#'>Зарегистрироваться</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel">Вход</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php" class="border p-3">
                        <?php include('serverFunc/errorsLog.php'); ?>
                        <div class="">
                            <label for="login" class="form-label text-dark">Логин</label>
                            <input type="text" class="form-control" id="login" name="loginLog" aria-describedby="loginHelp" />
                            <div id="loginHelp" class="form-text">Введите ваш логин</div>
                        </div>
                        <div class="">
                            <label for="passwordInput" class="form-label text-dark">Пароль</label>
                            <input type="password" class="form-control" id="passwordInput" name="passwordLog" />
                            <div id="passwordHelp" class="form-text">Введите ваш пароль</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2" name="loginUser">Войти</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="regModalLabel">Регистрация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php" class="border p-3">
                        <?php include('serverFunc/errorsReg.php'); ?>
                        <div class="">
                            <label for="login" class="form-label text-dark">Логин</label>
                            <input type="text" class="form-control" id="login" name="loginReg" aria-describedby="loginHelp" />
                            <div id="loginHelp" class="form-text">Введите ваш логин</div>
                        </div>
                        <div class="">
                            <label for="passwordInput" class="form-label text-dark">Пароль</label>
                            <input type="password" class="form-control" id="passwordInput" name="passwordReg" />
                            <div id="passwordHelp" class="form-text">Введите ваш пароль</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2" name="regUser">Зарегистрироваться</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-center m-1">Крестики-нолики</h2>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="game">
                    <p class="lead text-center">Выберите сторону</p>
                    <div class="row">
                        <div class="col text-center">
                            <input type="radio" class="btn-check" name="side" id="cross-ttt" value="X" autocomplete="off" checked>
                            <label class="btn btn-outline-dark btn-lg rounded-circle" for="cross-ttt">X</label>
                        </div>
                        <div class="col text-center">
                            <input type="radio" class="btn-check" name="side" id="circle-ttt" value="O" autocomplete="off">
                            <label class="btn btn-outline-dark btn-lg rounded-circle" for="circle-ttt">O</label>
                        </div>
                    </div>
                    <p class="lead text-center">Режим игры</p>
                    <div class="row">
                        <div class="col text-center">
                            <input type="radio" class="btn-check" name="mode" id="single" value="single" autocomplete="off" checked>
                            <label class="btn btn-outline-dark btn-lg" for="single">Одиночная игра</label>
                        </div>
                        <div class="col text-center">
                            <input type="radio" class="btn-check" name="mode" id="local" value="local" autocomplete="off">
                            <label class="btn btn-outline-dark btn-lg" for="local">Локальная игра</label>
                        </div>
                        <div class="col text-center">
                            <input type="radio" class="btn-check" name="mode" id="multiplayer" value="multi" autocomplete="off">
                            <label class="btn btn-outline-dark btn-lg" for="multiplayer">Игра по сети</label>
                        </div>
                    </div>
                    <div class="text-center m-3">
                        <button type="button" class="btn btn-success btn-lg" onclick="initGame()">
                            <h1>Начать игру</h1>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col">
                <p class="lead text-center">Топ 30 игроков</p>
            </div>
        </div>
    </div>
</body>

</html>