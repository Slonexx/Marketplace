@extends('layout')
@section('item', 'link_3')
@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">

        <div class="row gradient rounded p-2 pb-2" style="margin-top: -1rem">
            <div class="col-10" style="margin-top: 1.2rem"> <span class="text-white" style="font-size: 20px">  Настройки &#8594; заказы </span> </div>
            <div class="col-2 text-center">
                <img src="https://smarttis.kz/Config/logo.png" width="40%"  alt="">
                <div class="text-white" style="font-size: 11px; margin-top: 8px"> Топ партнёр сервиса МойСклад </div>
            </div>
        </div>

        <form class="mt-3" action="/Setting/order/{{ $accountId }}?isAdmin={{ $isAdmin }}" method="post">
        @csrf <!-- {{ csrf_field() }} -->

            <div class="row">
                <P class="col-sm-5 col-form-label"> Выберите на какой склад создавать заказ: </P>
                <div class="col-sm-7">
                    <select name="Store" class="form-select text-black " >
                        @foreach($Body_store as $Body_store_item)
                            @if ( $Store == $Body_store_item->name )
                                <option selected value="{{ $Body_store_item->name }}"> {{ ($Body_store_item->name) }} </option>
                            @else
                                <option value="{{ $Body_store_item->name }}"> {{ ($Body_store_item->name) }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>

            <div class="mt-2">
                <p>Создание документов при заказе из Kaspi </p>
                <div class="mt-2 row" >
                    <P class="col-sm-5 col-form-label"> Выберите на какую организацию создавать заказы: </P>
                    <div class="col-sm-7">


                        <select name="Organization"  id="hidden_Organization" class="form-select text-black" onclick="PaymentAccountFun()" >
                            @if ($Organization != "0")
                                <option selected value="{{ $Organization->id }}"> {{ ($Organization->name) }} </option>
                                @foreach($Body_organization as $bodyItem)
                                    @if ($Organization->id != $bodyItem->id)
                                        <option value="{{ $bodyItem->id }}"> {{ ($bodyItem->name) }} </option>
                                    @endif
                                @endforeach
                            @endif
                            @if ($Organization == "0")
                                @foreach($Body_organization as $bodyItem)
                                    <option value="{{ $bodyItem->id }}"> {{ ($bodyItem->name) }} </option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>
                <div class="mt-2 row">
                    <P class="col-sm-5 col-form-label"> Выберите какой тип документов создавать: </P>
                    <div class="col-sm-7">
                        <select name="Document" class="form-select text-black" >
                            @if($Document == "0")
                                <option selected value="0">Не создавать</option>
                                <option value="1">Отгрузка</option>
                                <option value="2">Отгрузка + счет-фактура выданный</option>
                            @endif
                            @if($Document == "1")
                                <option value="0">Не создавать</option>
                                <option selected value="1">Отгрузка</option>
                                <option value="2">Отгрузка + счет-фактура выданный</option>
                            @endif
                            @if($Document == "2")
                                <option value="0">Не создавать</option>
                                <option value="1">Отгрузка</option>
                                <option selected value="2">Отгрузка и счет-фактура выданный</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="mt-2 row">
                    <P class="col-sm-5 col-form-label"> Выберите какой тип платежного документа создавать: </P>
                    <div class="col-sm-7">
                        <select id="PaymentDocument" name="PaymentDocument" class="form-select text-black"  onclick="PaymentDocumentFun()">
                            @if($PaymentDocument == "0")
                                <option selected value="0">Не создавать</option>
                                <option value="1">Приходной ордер</option>
                                <option value="2">Входящий платёж </option>
                            @endif
                            @if($PaymentDocument == "1")
                                <option value="0">Не создавать</option>
                                <option selected value="1">Приходной ордер</option>
                                <option value="2">Входящий платёж </option>
                            @endif
                            @if($PaymentDocument == "2")
                                <option value="0">Не создавать</option>
                                <option value="1">Приходной ордер</option>
                                <option selected value="2">Входящий платёж </option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="mt-2" id="hidden_PaymentAccount">
                    <div class="row">
                        <P class="col-sm-5 col-form-label"> Выберите расчетный счет: </P>
                        <div class="col-sm-7">
                            @foreach($Body_organization as $row)
                                <div class="Payment" id="Payment_{{$row->id}}">
                                    @php
                                        $id = $row->id;
                                        $array_element = [];
                                        $url_accounts = "https://api.moysklad.ru/api/remap/1.2/entity/organization/".$id."/accounts";
                                        $clinet = new \App\Clients\MsClient($apiKey);
                                        $Body_accounts = $clinet->get($url_accounts)->rows;
                                    @endphp

                                    <select name="{{$row->id}}" class="form-select text-black">
                                        @if (array_key_exists(0, $Body_accounts))
                                            @if ($Organization != "0")
                                                @if ($Organization->id == $row->id)
                                                    <option selected value="{{$PaymentAccount}}"> {{ $PaymentAccount }}</option>
                                                @endif
                                            @endif
                                            @foreach ($Body_accounts as $Body_accounts_item)
                                                @if($PaymentAccount != $Body_accounts_item->accountNumber)
                                                    <option value="{{$Body_accounts_item->accountNumber}}"> {{ $Body_accounts_item->accountNumber }}</option>
                                                @endif
                                            @endforeach
                                        @else <option>Нет расчетного счёта</option>
                                        @endif
                                    </select>



                                </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr class="href_padding">

            <div class="mt-2">
                <div class="row">
                    <p>Выбор проверки при создании товара (из Kaspi в МойСклад)</p>
                    <p class="col-sm-5 col-form-label"> Выберите способ проверки товаров: </p>
                    <div class="col-sm-7 ">
                        <select name="CheckCreatProduct" class="form-select text-black">
                            @if($CheckCreatProduct == "1")
                                <option selected value="1">По артикулу</option>
                                <option value="2">По названию</option>
                                <option value="3">По артикулу и названию</option>
                            @endif
                            @if($CheckCreatProduct == "2")
                                <option value="1">По артикулу</option>
                                <option selected value="2">По названию</option>
                                <option value="3">По артикулу и названию</option>
                            @endif
                            @if($CheckCreatProduct == "3")
                                <option value="1">По артикулу</option>
                                <option value="2">По названию</option>
                                <option selected value="3">По артикулу и названию</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class='d-flex justify-content-end text-black btnP' >
                <button class="btn btn-outline-dark textHover" data-bs-toggle="modal" data-bs-target="#modal">
                    <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </button>
            </div>
        </form>
    </div>


<script>
    function PaymentAccountFun(){
        var select = document.getElementById('hidden_Organization');
        var option = select.options[select.selectedIndex];
        $(".Payment").hide();
        $("#Payment_" + option.value).show();
    }
    PaymentAccountFun();

    function PaymentDocumentFun(){
        var select = document.getElementById('PaymentDocument');
        var option = select.options[select.selectedIndex];
        if (option.value == 2){
            document.getElementById("hidden_PaymentAccount").style.display = "block";
        }else {
            document.getElementById("hidden_PaymentAccount").style.display = "none";
        }
    }
    PaymentDocumentFun()
</script>


@endsection



<style>
    .selected {
        margin-right: 0px !important;
        background-color: rgba(17, 17, 17, 0.14) !important;
        border-radius: 3px !important;
    }
    .dropdown-item:active {
        background-color: rgba(123, 123, 123, 0.14) !important;
    }

    .block {
        display: none;
        margin: 10px;
        padding: 10px;
        border: 2px solid orange;
    }

</style>
