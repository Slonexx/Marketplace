@extends('layout')

@section('content')

    <div class="content content p-4 mt-2 bg-white text-Black rounded">
        <h2 align="center">
            <i class="fa-solid fa-envelope text-orange"></i>
            Написать нам на почту </h2>

            @if(Session::has('message'))
                <div class="alert text-center">
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}  alert-dismissible fade show "
                         role="alert"> {{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <br>
                    </div>
                </div>
            @endif



        <div class="mt-3">
            <form action=" {{  route('Send', ['id' => $id] ) }} " method="post">


            @csrf <!-- {{ csrf_field() }} -->


                    <div class="form-group mb-3 row ">
                        <label for="TokenKaspi" class="col-sm-2 col-form-label mt-2">Введите Имя</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" placeholder="Введите Имя, фамилия" id="name" class="form-control form-control-orange"
                                   required maxlength="100" value="{{ old('name') ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group mb-3 row ">
                        <label for="TokenKaspi" class="col-sm-2 col-form-label mt-2">Адрес почты</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" placeholder="Адрес почты" id="email" class="form-control form-control-orange"
                                   required maxlength="100" value="{{ old('email') ?? '' }}">
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
                    <button type="submit" class="mt-3 btn btn-outline-dark textHover"> <i class="fa-solid fa-envelope"></i> Отправить  </button>
                </div>


            </form>



        </div>
    </div>

@endsection
