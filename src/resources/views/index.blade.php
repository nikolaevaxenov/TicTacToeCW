@extends('layout')

@section('jscssimport')
<link rel="stylesheet" href="{{ asset("css/index.css") }}">
<script src="{{ asset("js/index.js") }}"></script>
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
                        @foreach ($scoreboard as $score)
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
@endsection
