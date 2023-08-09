
@extends('popup.index')

@section('content')

    <script>

        const url = 'https://smartkaspi.kz/Popup/customerorder/show'
        //const url = 'https://rekassa/Popup/customerorder/show'
        let object_Id = ''
        let accountId = ''
        let entity_type = ''
        let id_ticket = ''

        let payment_type = ''
        let products_length = 0

        /*let receivedMessage = {
            "name":"OpenPopup",
            "messageId":1,
            "popupName":"fiscalizationPopup",
            "popupParameters":
                {
                    "object_Id":"ac0c9983-acec-11ed-0a80-06ac001abb0c",
                    "accountId":"1dd5bd55-d141-11ec-0a80-055600047495",
                    "entity_type":"customerorder",
                }
        };*/

        window.addEventListener("message", function() {
            FU_AnimationDownloadGIF('toggle', 'Загрузка')
            newPopup()
            let receivedMessage = event.data
            if (receivedMessage.name === 'OpenPopup') {
                object_Id = receivedMessage.popupParameters.object_Id;
                accountId = receivedMessage.popupParameters.accountId;
                entity_type = receivedMessage.popupParameters.entity_type;

                let data = { object_Id: object_Id, accountId: accountId, };

                // receivedMessage = null

                let settings = ajax_settings(url, "GET", data);
                console.log(url + ' settings ↓ ')
                console.log(settings)

                $.ajax(settings).done(function (json) {
                    console.log(url + ' response ↓ ')
                    console.log(json)

                    id_ticket = json.attributes.ticket_id
                    window.document.getElementById("numberOrder").innerHTML = json.name;
                    payment_type = json.application.payment_type - 1
                    console.log('payment_type = ' + payment_type)
                    let products = json.products;
                    products_length = json.products.length


                    for (let i = 0; i < products.length; i++) {
                        if (products[i].propety === true) {

                            let vat =  products[i].vat + '%'
                            let minus = 0
                            let plus = 1
                            if (products[i].vat === 0)  vat = "без НДС"

                            $('#main').append('<div id="'+i+'" class="divTableRow" >' +
                                '<div class="divTableCell">'+i+'</div>' +
                                '<div id="productId_'+i+'" class="divTableCell" style="display: none">'+products[i].position+'</div>' +
                                '<div id="productName_'+i+'" class="divTableCell"> '+products[i].name+'</div>' +

                                '<div class="divTableCell">' +
                                '<span><i onclick="updateQuantity('+ i +', '+minus+')" class="fa-solid fa-circle-minus text-danger" style="cursor: pointer"></i></span>' +
                                '<span id="productQuantity_'+ i +'" class="mx-3">' + products[i].quantity + '</span>' +
                                '<span><i onclick="updateQuantity( '+ i +', '+plus+')" class="fa-solid fa-circle-plus text-success" style="cursor: pointer"></i></span>' +
                                '</div>' +

                                '<div id="productUOM_'+i+'" class="divTableCell">'+products[i].uom['name']+'</div>' +
                                '<div id="productIDUOM_'+i+'" class="divTableCell" style="display: none">'+products[i].uom['id']+'</div>' +

                                '<div id="productPrice_'+ i +'" class="divTableCell"> '+ products[i].price +' </div>' +

                                '<div id="productVat_'+ i +'" class="divTableCell"> '+ vat + ' </div>' +

                                '<div id="productDiscount_'+ i +'" class="divTableCell"> '+ products[i].discount + '%' + ' </div>' +

                                '<div id="productFinal_'+ i +'" class="divTableCell"> '+ products[i].final + ' </div>' +

                                '<span onclick="deleteBTNClick('+ i +')" class="divTableCell" > <i class="fa-solid fa-rectangle-xmark" style="cursor: pointer; margin-left: 2rem" ></i> </span>' +

                                " </div>")

                            let sum = window.document.getElementById("sum").innerHTML
                            if (!sum) sum = 0
                            window.document.getElementById("sum").innerHTML = roundToTwo(parseFloat(sum) + parseFloat(products[i].final))

                        } else {

                            $('#main').append('<div id="'+i+'" class="divTableRow" style="display: none">' + " </div>")

                            window.document.getElementById("messageAlert").innerText = "Позиции, у которых не системные единицы измерения не могут быть добавлены "
                            window.document.getElementById("message").style.display = "block"
                        }
                    }

                    //window.document.getElementById('cash').value = window.document.getElementById("sum").innerHTML
                    FU_AnimationDownloadGIF('hide', 'Загрузка')
                    payment_type_on_set_option(payment_type, window.document.getElementById("sum").innerHTML)

                    if (json.attributes.ticket_id != null){
                        window.document.getElementById("ShowCheck").style.display = "block";
                        window.document.getElementById("refundCheck").style.display = "block";
                    } else {
                        window.document.getElementById("getKKM").style.display = "block";
                    }
                    window.document.getElementById("closeButtonId").style.display = "block";
                })

            }
        });


        function sendKKM(pay_type){
            let url = 'https://smartkaspi.kz/Popup/customerorder/send'
            //let url = 'https://rekassa/Popup/customerorder/send'

            let button_hide = ''
            if (pay_type === 'return') button_hide = 'refundCheck'
            if (pay_type === 'sell') button_hide = 'getKKM'

            window.document.getElementById(button_hide).style.display = "none"


            let total = window.document.getElementById('sum').innerText
            let money_card = window.document.getElementById('card').value
            let money_cash = window.document.getElementById('cash').value
            let money_mobile = window.document.getElementById('mobile').value
            let SelectorInfo = document.getElementById('valueSelector')
            let option = SelectorInfo.options[SelectorInfo.selectedIndex]
            let modalShowHide = 'show'

            let error_what = option_value_error_fu(option.value, money_cash, money_card, money_mobile)
            if (error_what === true) modalShowHide = 'hide'


            if (total-0.01 <= money_card+money_cash+money_mobile) {
                if (modalShowHide === 'show'){
                    window.document.getElementById('HeaderGIF').innerText = 'Отправка'
                    FU_AnimationDownloadGIF('toggle', 'Отправка')
                    let products = []
                    for (let i = 0; i < products_length; i++) {
                        if (window.document.getElementById(i).style.display !== 'none') {
                            products[i] = {
                                id:window.document.getElementById('productId_'+i).innerText,
                                name:window.document.getElementById('productName_'+i).innerText,
                                quantity:window.document.getElementById('productQuantity_'+i).innerText,
                                UOM:window.document.getElementById('productIDUOM_'+i).innerText,
                                price:window.document.getElementById('productPrice_'+i).innerText,
                                is_nds:window.document.getElementById('productVat_'+i).innerText,
                                discount:window.document.getElementById('productDiscount_'+i).innerText
                            }
                        }
                    }

                    let data =  {
                        "accountId": accountId,
                        "object_Id": object_Id,
                        "entity_type": entity_type,

                        "money_card": money_card,
                        "money_cash": money_cash,
                        "money_mobile": money_mobile,

                        "pay_type": pay_type,
                        "total": total,

                        "position": JSON.stringify(products),
                    }
                    console.log(url + ' data ↓ ')
                    console.log(data)

                    $.ajax({
                        url: url,
                        method: 'post',
                        dataType: 'json',
                        data: data,
                        success: function(response){
                            FU_AnimationDownloadGIF('hide', 'Отправка')
                            console.log(url + ' response ↓ ')
                            console.log(response)
                            if (response.code === 200){
                                window.document.getElementById("messageGoodAlert").innerText = "Чек создан"
                                window.document.getElementById("messageGood").style.display = "block"
                                window.document.getElementById("ShowCheck").style.display = "block"
                                window.document.getElementById("closeShift").style.display = "block"
                                id_ticket = response.res.response.id;
                            } else {
                                if (response.hasOwnProperty('res')) {
                                    if (response.res.hasOwnProperty('error')) {
                                        if (response.res.error.code === 'CASH_REGISTER_SHIFT_PERIOD_EXPIRED') {
                                            window.document.getElementById('messageAlert').innerText = "Предыдущая смена не закрыта !"
                                            window.document.getElementById('message').style.display = "block"
                                            window.document.getElementById(button_hide).style.display = "block"
                                        } else  {
                                            window.document.getElementById('messageAlert').innerText = response.res.error.code
                                            window.document.getElementById('message').style.display = "block"
                                            window.document.getElementById(button_hide).style.display = "block"
                                        }
                                    }
                                } else {
                                    window.document.getElementById('messageAlert').innerText = "Ошибка 400"
                                    window.document.getElementById('message').style.display = "block"
                                    window.document.getElementById(button_hide).style.display = "block"
                                }
                            }
                        },
                        error: function(errorResponse) {
                            console.log(url + ' errorResponse ↓ ')
                            console.log(errorResponse)
                            FU_AnimationDownloadGIF('hide', 'Отправка')
                            window.document.getElementById('messageAlert').innerText = "Ошибка 400. "+errorResponse.responseJSON.message
                            window.document.getElementById('message').style.display = "block"
                            window.document.getElementById(button_hide).style.display = "block"
                        },
                    });
                }
                else window.document.getElementById(button_hide).style.display = "block";
            } else {
                window.document.getElementById('messageAlert').innerText = 'Сумма некорректна, введите больше';
                window.document.getElementById('message').style.display = "block";
                window.document.getElementById(button_hide).style.display = "block";
            }
        }


    </script>


    <div class="main-container">
        <div class="row gradient rounded p-2">
            <div class="col-9">
                <div class="mx-2"> <img src="https://app.rekassa.kz/static/logo.png" width="45" height="45"  alt="">
                    <span class="text-white" style="font-size: 23px"> reKassa </span>
                    <span class="text-white" style="font-size: 20px; margin-left: 5.5rem">Заказ покупателя №</span>
                    <span id="numberOrder" class="text-white" style="font-size: 20px"></span>
                </div>
            </div>
            <div class="col-3">
                <div class="row"> <div class="col-6"></div>
                    <div class="col-6">
                        <button id="closeButtonId" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal" >Закрыть смену</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="message" class="mt-2 row" style="display:none;" >
            <div class="col-12">
                <div id="messageAlert" class=" mx-3 p-2 alert alert-danger text-center ">
                </div>
            </div>
        </div>
        <div id="messageGood" class="mt-2 row" style="display:none;" >
            <div class="col-12">
                <div id="messageGoodAlert" class=" mx-3 p-2 alert alert-success text-center ">
                </div>
            </div>
        </div>
        <div class="content-container">
            <div class=" rounded bg-white">
                <div class="row p-3">
                    <div class="divTable myTable">
                        <div class="divTableHeading">
                            <div class="divTableRow">

                                <div class="divTableHead text-black">№</div>
                                <div class="divTableHead text-black">Наименование</div>
                                <div class="divTableHead text-black">Кол-во</div>
                                <div class="divTableHead text-black">Ед. Изм.</div>
                                <div class="divTableHead text-black">Цена</div>
                                <div class="divTableHead text-black">НДС</div>
                                <div class="divTableHead text-black">Скидка</div>
                                <div class="divTableHead text-black">Сумма</div>
                                <div class="divTableHead text-black">Учитывать </div>
                                <div class="buttons-container-head mt-1"></div>

                            </div>
                        </div>
                        <div id="main" class="divTableBody">

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="buttons-container-head"></div>
        <div class="buttons-container">
            <div class="row">

                <div class="col-7 row">
                    <div class="row">
                        <div class="col-12 mx-2 ">
                            <div class="col-5 bg-success text-white p-1 rounded">
                                <span> Итого: </span>
                                <span id="sum"></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-1">

                </div>
                <div class="col-2">

                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button onclick="sendKKM('return')" id="refundCheck" class="btn btn-danger">возврат</button>
                    <button onclick="sendKKM('sell')" id="getKKM" class="btn btn-success">Отправить в ККМ</button>
                </div>

                <div class="row mt-2">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-5">
                                <div class="mx-1 mt-1 bg-warning p-1 rounded text-center">Тип оплаты</div>
                            </div>
                            <div class="col-7">
                                <select onchange="SelectorSum(valueSelector)" id="valueSelector" class="form-select">
                                    <option selected value="0">Наличными</option>
                                    <option value="1">Картой</option>
                                    <option value="2">Мобильная</option>
                                    <option value="3">Смешанная</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4"> <div id="Visibility_Cash" class="mx-2" style="display: none">
                                    <input id="cash" type="number" step="0.1" placeholder="Сумма наличных"  onkeypress="return isNumberKeyCash(event)"
                                           class="form-control float" required maxlength="255" value="">
                                </div> </div>
                            <div class="col-4"> <div id="Visibility_Card" class="mx-2" style="display: none">
                                    <input id="card" type="number" step="0.1"  placeholder="Сумма картой" onkeypress="return isNumberKeyCard(event)"
                                           class="form-control float" required maxlength="255" value="">
                                </div> </div>
                            <div class="col-4"> <div id="Visibility_Mobile" class="mx-2" style="display: none">
                                    <input id="mobile" type="number" step="0.1"  placeholder="Сумма мобильных" onkeypress="return isNumberKeyMobile(event)"
                                           class="form-control float" required maxlength="255" value="">
                                </div> </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-2 d-flex justify-content-end">
                        <button onclick="ShowCheck()" id="ShowCheck" class="btn btn-success">Показать чек</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="GIF" class="modal fade bd-example-modal-sm" data-bs-keyboard="false" data-bs-backdrop="static"
         tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> <i class="fa-solid fa-circle-exclamation text-danger"></i>
                        <span id="HeaderGIF">Загрузка</span>
                    </h5>
                </div>
                <div class="modal-body text-center" style="background-color: #ffffff">
                    <div class="row">
                        <img style="width: 100%" src="https://smartkaspi.kz/Config/download.gif" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Закрытие смены
                        <i class="fa-solid fa-circle-question text-danger"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label> Введите пин код для закрытия смены</label>
                            <input id="pin_code" type="number" placeholder="PIN code"
                                   class="form-control float" required maxlength="10" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  onclick="closeShift()" id="closeShift"
                             data-bs-dismiss="modal" class="btn btn-danger">Закрыть смену</button>
                </div>
            </div>
        </div>
    </div>
    @include('popup.script_popup_app')
    @include('popup.style_popup_app')
@endsection
