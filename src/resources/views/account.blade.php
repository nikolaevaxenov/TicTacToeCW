@extends('layout')

@section('jscssimport')
<style>
    #apikey {
        -webkit-touch-callout: all;
        -webkit-user-select: all;
        -khtml-user-select: all;
        -moz-user-select: all;
        -ms-user-select: all;
        user-select: all;
        border: 1px solid gray;
        font-size: large;
    }
</style>
@endsection

@section('pageTitle', 'Личный кабинет')

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col">
            <h2 class="text-center">Статистика игр</h2>
            <table class="table table-hover mx-3">
                <tbody>
                    <tr>
                        <th scope="row" class="text-center">Количество очков</th>
                        <td class="text-center">{{$score[0]['score']}}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center">Количество игр</th>
                        <td class="text-center">{{$score[0]['wins'] + $score[0]['losses']}}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center">Победы</th>
                        <td class="text-center">{{$score[0]['wins']}}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center">Поражения</th>
                        <td class="text-center">{{$score[0]['losses']}}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center">Отношение побед к поражениям</th>
                        <td class="text-center">
                            @if ($score[0]['wins'] != 0 && $score[0]['losses'] != 0)
                                {{round($score[0]['wins'] / $score[0]['losses'], 2)}}
                            @else
                                @if ($score[0]['wins'] != 0)
                                    {{$score[0]['wins']}}
                                @else
                                    0
                                @endif
                            @endif
                        </td>
                    </tr>
                </tbody>
              </table>
        </div>
    </div>
</div>
@endsection
