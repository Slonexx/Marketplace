@extends('layout')
@section('item', 'link_2')
@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">

        <div class="row gradient rounded p-2 pb-2" style="margin-top: -1rem">
            <div class="col-10" style="margin-top: 1.2rem"> <span class="text-white" style="font-size: 20px">  Настройки &#8594; настройки интеграции </span> </div>
            <div class="col-2 text-center">
                <img src="https://smarttis.kz/Config/logo.png" width="40%"  alt="">
                <div class="text-white" style="font-size: 11px; margin-top: 8px"> Топ партнёр сервиса МойСклад </div>
            </div>
        </div>

        @isset($message)
            <div class="mt-2 {{$message['alert']}}"> {{ $message['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endisset

        <form class="mt-3" action="/Setting/main/{{ $accountId }}?isAdmin={{ $isAdmin }}" method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <div class="mb-3 row">
                <label for="TokenKaspi" class="col-sm-2 col-form-label"> <i class="text-danger">*</i> Токен Kaspi </label>
                <div class="col-sm-10">
                    <input type="text" name="TokenKaspi" placeholder="Token ключ от Kaspi" id="TokenKaspi" class="form-control form-control-orange"
                           required maxlength="255" value="{{ $TokenKaspi }}">
                </div>
            </div>

            <div class='d-flex justify-content-end text-black btnP' >
                <button class="btn btn-outline-dark textHover" data-bs-toggle="modal" data-bs-target="#modal">
                    <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </button>
            </div>
        </form>
    </div>





@endsection

