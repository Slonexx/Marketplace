
@extends('layout')

@section('content')

    <script>
        var GlobalURL = 'http://rekassa/delete/Device/'

        var Global_num_click = {{ count($devices) }};
        var Global_Max_device = 0;
        var Global_device_1 = 0;
        var Global_device_2 = 0;

        function add_device(){
            for (var i = 0; i<=1; i++) {
                if (Global_num_click >= 1) {
                    Global_device_1 = 1;
                    //Global_device_2 = 1;
                    document.getElementById("device_1").style.display = "block";
                    //document.getElementById("device_2").style.display = "block";
                }
                if (Global_num_click >= 0) {
                    Global_device_1 = 1;
                    document.getElementById("device_1").style.display = "block";
                }
                if ( Global_num_click >= 1 ){
                    Global_Max_device = 1;
                    Global_device_1 = 1;
                    //Global_device_2 = 1;
                    document.getElementById("Max_device").style.display = "block";
                    break;
                }
                Global_num_click++;
                break;
            }

        }

        function delete_device_1(){
                Global_device_1 = 0;
                Global_num_click = 0;
                document.getElementById("device_1").style.display = "none";

                var ZHM_1 = document.getElementById('ZHM_1');

                    var xmlHttpRequest = new XMLHttpRequest();
                    xmlHttpRequest.open('GET', GlobalURL+ZHM_1.value);
                    xmlHttpRequest.send();


                var PASSWORD_1 = document.getElementById('PASSWORD_1');
                ZHM_1.value = '';
                PASSWORD_1.value = '';

                if (Global_Max_device === 1) {
                    Global_Max_device = 0;
                    document.getElementById("Max_device").style.display = "none";
                }
        }

        function delete_device_2(){
                Global_device_2 = 0;
                Global_num_click = 1;
                document.getElementById("device_2").style.display = "none";

                var ZHM_2 = document.getElementById('ZHM_2');

                    var xmlHttpRequest = new XMLHttpRequest();
                    xmlHttpRequest.open('GET', GlobalURL+ZHM_2.value);
                    xmlHttpRequest.send();

                var PASSWORD_2 = document.getElementById('PASSWORD_2');
                ZHM_2.value = '';
                PASSWORD_2.value = '';

                if (Global_Max_device === 1) {
                    Global_Max_device = 0;
                    document.getElementById("Max_device").style.display = "none";
                }
        }

        function clearVal(Val){
            Val.value = '';
        }

    </script>

    @php

        $Visible = [];
        $device_1 = null;
        $device_2 = null;
            if (isset($devices)) {
                foreach ($devices as $id=>$i) {
                    if ($i->position == 1) {
                        $device_1 = [
                            'visible'=> 'display: block;',
                            'znm'=> $i->znm,
                            'password'=> $i->password,
                             ];
                    }
                    if ($i->position == 2) {
                        $device_2 = [
                            'visible'=> 'display: block;',
                            'znm'=> $i->znm,
                            'password'=> $i->password,
                            ];
                    }
                }
            }
        if ($device_1 == null){
             $device_1 = [
                 'visible'=> 'display: none;',
                 'znm'=> "",
                 'password'=> "",
                 ];
        }
        if ($device_2 == null){
             $device_2 = [
                 'visible'=> 'display: none;',
                 'znm'=> "",
                 'password'=> "",
                 ];
        }

    @endphp

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">

            <div class="row rekassa_gradient rounded p-2">
                <div class="col-10">
                    <div class="mx-2"> <img src="https://app.rekassa.kz/static/logo.png" width="35" height="35"  alt="">
                        <span class="text-white"> Настройки &#8594; фискализация &#8594; кассовый аппарат </span>
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


        <form action="/Setting/Device/{{$accountId}}?isAdmin={{ $isAdmin }}" method="post" class="mt-3">
        @csrf <!-- {{ csrf_field() }} -->

            <div id="device_1" style="{{$device_1['visible']}}" >

                <div class="row mb-2">
                    <div class="col-10">
                        <div class="mx-3"> <h5>Кассовый аппарат №1</h5></div>
                    </div>
                    <div class="col-2">
                        <button onclick="delete_device_1()" type="button" class=" mx-3 mt-1 btn btn-danger "> <i class="fa-solid fa-circle-plus"></i> удалить </button>
                    </div>
                </div>

                <div class="mb-3 row mx-4">
                    <div class="col-4">
                        1. <label class="mt-1"> Заводской номер (ЗНМ) </label>
                    </div>
                    <div class="col-8 row">
                        <div class="col-12 input-group">
                            <input type="text" name="ZHM_1" id="ZHM_1" placeholder="Необходимо ввести значение"
                                   class="form-control" maxlength="255" value="{{$device_1['znm']}}">
                            <div class="input-group-append">
                                <button onclick="clearVal(ZHM_1)" type="button" class="btn btn-outline-secondary">Очистить</button>
                            </div>
                        </div>

                    </div>
                    <div class="mt-2"></div>
                    <div class="col-4">
                        2. <label class="mt-1"> Пароль </label>
                    </div>
                    <div class="col-8 row">
                        <div class="col-12 input-group">
                            <input type="text" name="PASSWORD_1" id="PASSWORD_1" placeholder="Необходимо ввести значение"
                                   class="form-control" maxlength="255" value="{{$device_1['password']}}">
                            <div class="input-group-append">
                                <button onclick="clearVal(PASSWORD_1)" type="button" class="btn btn-outline-secondary">Очистить</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="device_2" style="{{$device_2['visible']}}" >
                <div class="row mb-2">
                    <div class="col-10">
                        <div class="mx-3"> <h5>Кассовый аппарат №2</h5></div>
                    </div>
                    <div class="col-2">
                        <button onclick="delete_device_2()" type="button" class=" mx-3 mt-1 btn btn-danger "> <i class="fa-solid fa-circle-plus"></i> удалить </button>
                    </div>
                </div>

                <div class="mb-3 row mx-4">
                    <div class="col-4">
                        1. <label class="mt-1"> Заводской номер (ЗНМ) </label>
                    </div>
                    <div class="col-8 row">
                        <div class="col-12 input-group">
                            <input type="text" name="ZHM_2" id="ZHM_2" placeholder="Необходимо ввести значение"
                                   class="form-control" maxlength="255" value="{{$device_2['znm']}}">
                            <div class="input-group-append">
                                <button onclick="clearVal(ZHM_2)" type="button" class="btn btn-outline-secondary">Очистить</button>
                            </div>
                        </div>

                    </div>
                    <div class="mt-2"></div>
                    <div class="col-4">
                        2. <label class="mt-1"> Пароль </label>
                    </div>
                    <div class="col-8 row">
                        <div class="col-12 input-group">
                            <input type="text" name="PASSWORD_2" id="PASSWORD_2" placeholder="Необходимо ввести значение"
                                   class="form-control" maxlength="255" value="{{$device_2['password']}}">
                            <div class="input-group-append">
                                <button onclick="clearVal(PASSWORD_2)" type="button" class="btn btn-outline-secondary">Очистить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="href_padding">



            <button class="btn btn-outline-dark textHover" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </button>


        </form>
    </div>


@endsection

<style>
    .rekassa_gradient{
        /* background: rgb(145,0,253);
         background: linear-gradient(34deg, rgba(145,0,253,1) 0%, rgba(232,0,141,1) 100%);*/
        background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );
    }
</style>

