@extends('layout')

@section('content')

    <div class="content">
        <h2 align="center">
            <i class="fa-solid fa-envelope"></i>
            Написать нам на почту </h2>


            @if(Session::has('message'))
                <div class="alertheight">
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" role="alert">
                        {{ Session::get('message') }}
                    </div>
                </div>
            @endif



        <div class="form_support">
            <form action=" {{  route('Send') }} " method="post">


            @csrf <!-- {{ csrf_field() }} -->

                <div class="form-group">
                    <input type="text" name="name" placeholder="Введите Имя, фамилия" id="name" class="form-control"
                           required maxlength="100" value="{{ old('name') ?? '' }}">
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Адрес почты" id="email" class="form-control"
                           required maxlength="100" value="{{ old('email') ?? '' }}">
                </div>

                <div class="form-group">
                    <textarea class="form-control" name="message" placeholder="Ваше сообщение"
                              required maxlength="500" rows="3">{{ old('message') ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-envelope"></i>
                    Отправить  </button>
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

