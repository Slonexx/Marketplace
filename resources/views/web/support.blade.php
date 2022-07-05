@extends('layout')

@section('content')
    <br class="content">
    <div class="content">
        <h2 align="center">Написать нам на почту</h2>
        <div class="form_support">
            <form action="/contact/submit" method="post">
                <div class="form-group">
                    <label for="name">Введите имя </label>
                    <input type="text" name="name" placeholder="Введите Имя" id="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="name">Введите Email </label>
                    <input type="text" name="email" placeholder="Введите email" id="email" class="form-control">
                </div>

                <div class="form-group">
                    <label for="subject">Тема сообщения </label>
                    <input type="text" name="subject" placeholder="Введите тему сообщения" id="subject" class="form-control">
                </div>

                <div class="form-group">
                    <label for="message">Cообщения</label>
                    <textarea name="message" id="message" class="form-control" placeholder="Введите сообщение"></textarea>
                </div>

                <br>
                <button type="submit" class="btn btn-success"> Отправить </button>
                <br>
            </form>

        </div>
    </div>

@endsection

<style>
    .form_support{
        padding: 30px;
    }
    .form_support label{
        padding-left: 2px;
        padding-top: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
    }

    .form_support button{
        background: green;
       float: right;
    }
    .form_support button:hover{
        background: black;
        float: right;
    }



    .content{
        padding-top: 0px;
        margin: 5px;
        background: white;
        border-radius: 10px;
    }
    .content h2{
        padding-top: 10px;
    }

    .content br{
        padding: 5px;
    }


</style>

