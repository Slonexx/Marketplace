
@extends('layout')
@section('item', 'link_8')
@section('content')

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">

        <div class="row rekassa_gradient rounded p-2">
            <div class="col-10">
                <div class="mx-2"> <img src="https://app.rekassa.kz/static/logo.png" width="35" height="35"  alt="">
                    <span class="text-white"> Настройки &#8594; ReKassa &#8594; кассовый аппарат </span>
                </div>
            </div>
            <div class="col-2">
                <button onclick="add_device()" type="button" class=" btn transparent btn-outline-warning"> <i class="fa-solid fa-circle-plus"></i> Добавить </button>
            </div>
        </div>


        @isset($message)

            <div class="mt-2 {{$message['alert']}}"> {{ $message['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @endisset

        <div id="Max_device" class="alert alert-danger alert-dismissible fade show in text-center mt-2"
             role="alert" style="display: none"> Уже максимальное количество кассовых аппаратов </div>


        <form action="/Setting/Automation/{{ $accountId }}?isAdmin={{ $isAdmin }}" method="post">
            @csrf <!-- {{ csrf_field() }} -->
            <div class="mt-2 row p-1 gradient_invert rounded text-black">
                <div class="col-11">
                    <div style="font-size: 20px"> Создать сценарий </div>
                </div>
                <div onclick="createScript()" onmousedown="showAddingOff()" onmouseup="showAddingOn()" class="col-1 d-flex justify-content-end " style="font-size: 30px; cursor: pointer">
                    <i id="adding_off" class="fa-regular fa-square-plus"></i>
                    <i id="adding_on" class="fa-solid fa-square-plus" style="display: none"></i>
                </div>
            </div>

            <div class="mt-2 row gradient p-1 rounded text-white">
                <div class="col-2">
                    Тип документа
                </div>
                <div class="col-2 text-center">
                    Статус
                </div>
                <div class="col-2 text-center">
                    Тип оплаты
                </div>
                <div class="row col-5">
                    <div class="col-6 text-center">
                        Канал продаж
                    </div>
                    <div class="col-6 text-center">
                        Проект
                    </div>
                </div>

                <div class="col-1 text-center">
                    Удалить
                </div>
            </div>
            <div id="mainCreate">

            </div>

            <div id="hiddenAllComponent" style="display: none">

            </div>
            <button class="mt-2 btn btn-outline-dark gradient_focus"> Сохранить</button>
        </form>
    </div>

    @include('setting.LetScript')
    @include('setting.function')
@endsection

<style>
    .rekassa_gradient{
        /* background: rgb(145,0,253);
         background: linear-gradient(34deg, rgba(145,0,253,1) 0%, rgba(232,0,141,1) 100%);*/
        background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );
    }
</style>

