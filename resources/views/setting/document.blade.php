
@extends('layout')
@section('item', 'link_7')
@section('content')

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">

        <div class="row rekassa_gradient rounded p-2">
            <div class="col-10">
                <div class="mx-2"> <img src="https://app.rekassa.kz/static/logo.png" width="35" height="35"  alt="">
                    <span class="text-white"> Настройки &#8594; ReKassa &#8594; документ </span>
                </div>
            </div>
        </div>


        @isset($message)

            <div class="mt-2 {{$message['alert']}}"> {{ $message['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        @endisset

        <form action="/Setting/Document/{{ $accountId }}?isAdmin={{ $isAdmin }}" method="post" class="mt-3">
        @csrf <!-- {{ csrf_field() }} -->

            <div class="row p-1 gradient_invert rounded text-black">
                <div class="col-11">
                    <div style="font-size: 20px">Документ</div>
                </div>
                <div onclick="toggleClick(1)" class="col-1 d-flex justify-content-end " style="font-size: 30px; cursor: pointer">
                    <i id="toggle_off" class="fa-solid fa-toggle-off" style="display: block"></i>
                    <i id="toggle_on"  class="fa-solid fa-toggle-on" style="display: none"></i>
                </div>
            </div>
            <div id="DOCUMENT" class="mt-2 mx-2 mb-2" style="display: block">
                <div class="row">
                    <div class="col-6">
                        <label class="mt-1 mx-4"> Выберите какой тип платежного документа создавать: </label>
                    </div>
                    <div class="col-6">
                        <select id="createDocument_asWay" name="createDocument_asWay" class="form-select text-black" onchange="asWay(this.value)" >
                            <option value="0"> Не создавать </option>
                            <option value="1"> Приходной ордер </option>
                            <option value="2"> Входящий платёж </option>
                            <option value="3"> От выбора типа оплаты </option>
                            <option value="4"> Собственный выбор </option>
                        </select>
                    </div>
                </div>
                <div id="CustomCreateDocument" class="mt-2 mb-2" style="display: none">
                    <div class="row">
                        <div class="col-6">
                            <label class="mt-1 mx-4"> Оплата наличными </label>
                        </div>
                        <div class="col-6">
                            <select id="OperationCash" name="OperationCash" class="form-select text-black" >
                                <option value="0"> Не создавать </option>
                                <option value="1"> Приходной ордер </option>
                                <option value="2"> Входящий платёж </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mt-1 mx-4"> Оплата картой </label>
                        </div>
                        <div class="col-6">
                            <select id="OperationCard" name="OperationCard" class="form-select text-black" >
                                <option value="0"> Не создавать </option>
                                <option value="1"> Приходной ордер </option>
                                <option value="2"> Входящий платёж </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mt-1 mx-4"> Оплата мобильной </label>
                        </div>
                        <div class="col-6">
                            <select id="OperationMobile" name="OperationMobile" class="form-select text-black" >
                                <option value="0"> Не создавать </option>
                                <option value="1"> Приходной ордер </option>
                                <option value="2"> Входящий платёж </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row p-1 mt-1 gradient_invert rounded text-black ">
                <div class="col-11">
                    <div style="font-size: 20px">Тип оплаты по умолчанию</div>
                </div>
                <div onclick="toggleClick(2)" class="col-1 d-flex justify-content-end " style="font-size: 30px; cursor: pointer">
                    <i id="toggle_off_2" class="fa-solid fa-toggle-off" style="display: block"></i>
                    <i id="toggle_on_2"  class="fa-solid fa-toggle-on" style="display: none"></i>
                </div>
            </div>
            <div id="Default_payment_type" class="mt-2 mx-2 mb-2" style="display: block">
                <div class="row">
                    <div class="col-6">
                        <label class="mt-1 mx-4"> Выберите тип оплаты </label>
                    </div>
                    <div class="col-6">
                        <select id="payment_type" name="payment_type" class="form-select text-black" >
                            <option value="1"> Оплата наличными </option>
                            <option value="2"> Оплата картой </option>
                        </select>
                    </div>
                </div>
            </div>
            <hr class="href_padding">

            <button class="btn btn-outline-dark " data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </button>
        </form>
    </div>

    <script>

        let createDocument = "{{ $paymentDocument }}"
        let payment_type = "{{ $payment_type }}"
        let OperationCash = "{{ $OperationCash }}"
        let OperationCard = "{{ $OperationCard }}"
        let OperationMobile = "{{ $OperationMobile }}"

        loading(createDocument, payment_type, OperationCash, OperationCard, OperationMobile)
        NAME_HEADER_TOP_SERVICE("Настройки → Документ")

        function asWay(value) {
            let OperationCash = window.document.getElementById('OperationCash')
            let OperationCard = window.document.getElementById('OperationCard')
            let CustomCreateDocument = window.document.getElementById('CustomCreateDocument')

            if (value === '4'){
                CustomCreateDocument.style.display = "block"
            } else {
                CustomCreateDocument.style.display = "none"
                OperationCash.value = 0
                OperationCard.value = 0
            }
        }

        function loading(createDocument, payment_type, OperationCash, OperationCard, OperationMobile){
            window.document.getElementById('createDocument_asWay').value = createDocument
            if (createDocument == 4) window.document.getElementById('CustomCreateDocument').style.display = "Block"
            window.document.getElementById('payment_type').value = payment_type

            window.document.getElementById('OperationCash').value = OperationCash
            window.document.getElementById('OperationCard').value = OperationCard
            window.document.getElementById('OperationMobile').value = OperationMobile
        }

        function toggleClick(id){

            if (id === 1){
                let toggle_off = window.document.getElementById('toggle_off')
                let toggle_on = window.document.getElementById('toggle_on')

                let DOCUMENT = window.document.getElementById('DOCUMENT')

                if (toggle_off.style.display == "none"){
                    toggle_on.style.display = "none"
                    toggle_off.style.display = "block"

                    DOCUMENT.style.display = 'block'
                } else {
                    toggle_on.style.display = "block"
                    toggle_off.style.display = "none"

                    DOCUMENT.style.display = 'none'
                }
            }

            if (id === 2) {
                let toggle_off_2 = window.document.getElementById('toggle_off_2')
                let toggle_on_2 = window.document.getElementById('toggle_on_2')

                let  Default_payment_type = window.document.getElementById('Default_payment_type')
                if (toggle_off_2.style.display == 'none'){
                    toggle_on_2.style.display = "none"
                    toggle_off_2.style.display = "block"

                    Default_payment_type.style.display = 'block'
                } else {
                    toggle_on_2.style.display = "block"
                    toggle_off_2.style.display = "none"

                    Default_payment_type.style.display = 'none'
                }
            }



        }

    </script>
@endsection

<style>
    .rekassa_gradient{
        /* background: rgb(145,0,253);
         background: linear-gradient(34deg, rgba(145,0,253,1) 0%, rgba(232,0,141,1) 100%);*/
        background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );
    }
</style>



