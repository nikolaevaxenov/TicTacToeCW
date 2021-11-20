<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.1/css/bootstrap.min.css" />

    <script src="https://kit.fontawesome.com/c4cafcfd34.js" crossorigin="anonymous"></script>

    @yield('jscssimport')

    <link rel="icon" href="tictactoe.svg">
    <title>@yield('pageTitle')</title>
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
                    <a class="nav-link" href="{{ route('index') }}">Играть</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('rules') }}">Правила игры</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto me-3">
                <li class="nav-item">
                    @if (Session::get('login'))
                        <a class='nav-link' href='{{ route('account') }}'><i class='fas fa-user me-1'></i>{{ Session::get('login') }}</a>
                    @else
                        <a class='nav-link' href='#'><i class='fas fa-user-secret me-1'></i>Гость</a>
                    @endif
                </li>
                    @if (Session::get('login'))
                        <li class='nav-item'>
                            <form method='post' action='{{ route('reglog') }}'>
                                @csrf
                                <button type='submit' class='btn btn-secondary' name='logout'>Выйти</button>
                            </form>
                        </li>
                    @else
                        <li class='nav-item'>
                            <a class='nav-link' data-bs-toggle='modal' data-bs-target='#loginModal' href='#'>Войти</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' data-bs-toggle='modal' data-bs-target='#regModal' href='#'>Зарегистрироваться</a>
                        </li>
                    @endif
            </ul>
        </div>
    </nav>

    <div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="regModalLabel">Регистрация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('reglog') }}" class="border p-3">
                        @csrf
                        @if (Session::get('failReg'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('failReg') }}
                            </div>
                        @endif
                        <div class="">
                            <label for="login" class="form-label text-dark">Логин</label>
                            <input type="text" class="form-control" id="login" name="loginReg" aria-describedby="loginHelp" required />
                            <div id="loginHelp" class="form-text">Введите ваш логин</div>
                        </div>
                        <span class="text-danger">@error('loginReg'){{ $message }} {{Session::flash('regStatus', 1)}}@enderror</span>
                        <div class="">
                            <label for="passwordInput" class="form-label text-dark">Пароль</label>
                            <input type="password" class="form-control" id="passwordInput" name="passwordReg" required />
                            <div id="passwordHelp" class="form-text">Введите ваш пароль</div>
                        </div>
                        <span class="text-danger">@error('passwordReg'){{ $message }} {{Session::flash('regStatus', 1)}}@enderror</span>
                        <div class="">
                            <label for="passwordInput2" class="form-label text-dark">Подтвердите пароль</label>
                            <input type="password" class="form-control" id="passwordInput2" name="passwordReg_confirmation" required />
                            <div id="passwordHelp2" class="form-text">Введите ваш пароль еще раз</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2" name="regUser" id="regSubmit">Зарегистрироваться</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel">Вход</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('reglog') }}" class="border p-3">
                        @csrf
                        @if (Session::get('failLog'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('failLog') }}
                            </div>
                        @endif
                        <div class="">
                            <label for="login" class="form-label text-dark">Логин</label>
                            <input type="text" class="form-control" id="login" name="loginLog" aria-describedby="loginHelp" required />
                            <div id="loginHelp" class="form-text">Введите ваш логин</div>
                        </div>
                        <span class="text-danger">@error('loginLog'){{ $message }}@enderror</span>
                        <div class="">
                            <label for="passwordInput" class="form-label text-dark">Пароль</label>
                            <input type="password" class="form-control" id="passwordInput" name="passwordLog" required />
                            <div id="passwordHelp" class="form-text">Введите ваш пароль</div>
                        </div>
                        <span class="text-danger">@error('passwordLog'){{ $message }}@enderror</span>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2" name="loginUser" id="logSubmit">Войти</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <script>
        var logModal = new bootstrap.Modal(document.getElementById('loginModal'));
        var regModal = new bootstrap.Modal(document.getElementById('regModal'));

        var logErrors = @if (Session::get('logStatus') == 1) 1 @else 0 @endif;
        var regErrors = @if (Session::get('regStatus') == 1) 1 @else 0 @endif;

        if (logErrors == 1) {
            logModal.show();
            {{Session::forget('logStatus')}}
        }
        if (regErrors == 1) {
            regModal.show();
            {{Session::forget('regStatus')}}
        }
    </script>
</body>

</html>
