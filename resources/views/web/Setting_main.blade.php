<?php session_start(); ?>
@extends('layout')

@section('content')

    <div class="content">
        <h2 align="center">Основные настройки</h2>
        <div class="form_support">
            <form action=" {{  route('Setting_Send') }} " method="post">
            @csrf <!-- {{ csrf_field() }} -->

                <div class="form-group">
                    <input type="text" name="API_KEY" placeholder="API ключ от Kaspi" id="API_KEY" class="form-control"
                           required maxlength="255"
                           value="<?php
                           if(isset($_SESSION['API_KEY'])) {
                               echo ($_SESSION["API_KEY"]);
                           }
                           else {
                               session_destroy();
                               echo "";
                           }
                           ?>">
                </div>

                <button type="submit" class="btn btn-primary"> Сохранить </button>

                @include('sweetalert::alert')

            </form>

        </div>
    </div>

@endsection

<style>

    .form_support{
        padding-top: 10px;
        padding-left: 40px;
        padding-right: 40px;
        padding-bottom: 40px;
    }

    .form_support button{
        margin-top: 10px;
        margin-right: 10px;
        background: green;
        float: right;
    }
    .form_support button:hover{
        background: black;
        float: right;
    }

    .content{
        padding-top: 0;
        margin: 5px;
        background: white;
        border-radius: 10px;
    }
    .content h2{
        padding-top: 10px;
    }

    .content div{
        padding-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }


</style>

