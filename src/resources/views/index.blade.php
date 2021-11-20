@extends('layout')

@section('jscssimport')
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta property="js_f" content="@if ($data['fielddb'] != null) {{$data['fielddb']['field']}} @else 0 @endif" />
<meta property="js_fielddb" content="@if ($data['fielddb'] != null) {{$data['fielddb']['field']}} @else 0 @endif" />
<meta property="js_login" content='@if (session()->has('login')) {{session()->get('login')}} @else {{session()->get('guestuuid')}} @endif' />
<meta property="js_url" content='{{route('reglog')}}' />
<meta property="js_side" content="
@if ($data['fielddb'] != null)
@if ($data['fielddb']['player1'] == null)
O
@else
X
@endif
@else
0
@endif
" />
<meta property="js_mode" content='@if ($data['fielddb'] != null) {{$data['fielddb']['mode']}} @else 0 @endif' />

<link rel="stylesheet" href="{{ asset("css/index.css") }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset("js/index.js") }}"></script>
<script>
    var t;

    function initGame() {
        var fielddb = document.head.querySelector("[property~=js_fielddb][content]").content.replace(/\s/g, '');
        var login = document.head.querySelector("[property~=js_login][content]").content.replace(/\s/g, '');
        var url = document.head.querySelector("[property~=js_url][content]").content;
        var side = document.head.querySelector("[property~=js_side][content]").content.replace(/\s/g, '');
        var mode = document.head.querySelector("[property~=js_mode][content]").content.replace(/\s/g, '');

        console.log(`fielddb|${fielddb}|${typeof fielddb}`);
        console.log(`login|${login}|${typeof login}`);
        console.log(`url|${url}|${typeof url}`);
        console.log(`side|${side}|${typeof side}`);
        console.log(`mode|${mode}|${typeof mode}`);

        t = new TicTacToe(fielddb, login, url, side, mode);
    }
</script>
@endsection

@section('pageTitle', 'Крестики-нолики')

@section('content')

<h2 class="text-center m-1">Крестики-нолики</h2>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="game center-block mt-3">
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
                    </div>
                    <div class="text-center m-3">
                        <button type="button" class="btn btn-success btn-lg" onclick="initGame()">
                            <h1>Начать игру</h1>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <p class="lead text-center">Топ 30 игроков</p>
                <table class="table mx-3">
                    <thead>
                      <tr>
                        <th scope="col" class="text-center">Логин</th>
                        <th scope="col" class="text-center">Счет</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['scoreboard'] as $score)
                            <tr @if ($score['login'] == session()->get('login'))
                                class="table-active"
                                @endif
                            </tr>
                                <th scope="row" class="text-center">{{$score['login']}}</th>
                                <td class="text-center">{{$score['score']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <script>
        var f = document.head.querySelector("[property~=js_fielddb][content]").content;
        if (f != 0) {
            initGame();
        }
    </script>
@endsection
