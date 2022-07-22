@extends('layout')

@section('content')


    <div class="content p-4 mt-2 bg-white text-black rounded">
        <h2 align="center">Написать в WhatsApp
            <i class="fa-brands fa-whatsapp text-success"></i>
        </h2>

        @if(Session::has('whatsapp'))
            <div class="alertheight">
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}" role="alert">
                    {{ Session::get('whatsapp') }}
                </div>
                <?php
                $url = Session::get('whatsapp_url');
                echo "<script>window.open('".$url."', '_blank')</script>";
                ?>

            </div>
        @endif



        <div class="mt-3">
            <form action=" {{  route('whatsapp_Send', ['accountId' => $accountId] ) }} " method="post">

            @csrf <!-- {{ csrf_field() }} -->
                <div class="form-group mb-3 row ">
                    <label for="TokenKaspi" class="col-sm-2 col-form-label mt-2">Введите Имя</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" placeholder="Введите Имя, фамилия" id="name" class="form-control form-control-orange"
                               required maxlength="100" value="{{ old('name') ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TokenKaspi" class="col-sm-2 col-form-label mt-2"></label>
                    <div class="col-sm-10">
                        <textarea class="form-control form-control-orange" name="message" placeholder="Ваше сообщение"
                                  required maxlength="500" rows="3">{{ old('message') ?? '' }}</textarea>
                    </div>
                </div>

                <div class='d-flex justify-content-end text-black btnP' >
                    <button type="submit" class="mt-3 btn btn-outline-dark "> <i class="fa-brands fa-whatsapp"></i> Отправить </button>
                </div>


            </form>

        </div>
    </div>


@endsection



