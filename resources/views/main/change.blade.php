@extends('layout')
@section('item', 'link_8')
@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">

        <div class="row rekassa_gradient rounded p-2 pb-2" style="margin-top: -1rem">
            <div class="col-10" style="margin-top: 1.2rem"> <span class="text-white" style="font-size: 20px"> ReKassa &#8594; Смена </span> </div>
            <div class="col-2 text-center">
                <img src="https://smarttis.kz/Config/logo.png" width="40%"  alt="">
                <div class="text-white" style="font-size: 11px; margin-top: 8px"> Топ партнёр сервиса МойСклад </div>
            </div>
        </div>

            <div id="message_good" class="mt-2 alert alert-success alert-dismissible fade show in text-center" style="display: none">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div id="message" class="mt-2 alert alert-danger alert-dismissible fade show in text-center" style="display: none">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>


        <form class="mt-3" action="" method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <div class="row">
                <label for="idKassa" class="col-3 col-form-label"> Выберите кассу </label>
                <div class="col-6">
                    <select id="idKassa" name="idKassa" class="form-select text-black" onchange="idKassaCheck()">
                        @foreach( $kassa as $item)
                            <option value="{{ $item->password }}"> {{ $item->znm }} </option>
                        @endforeach
                    </select>
                </div>
                <div id="is_activated" class="col-3 bg-success text-white p-1 col-form-label text-center rounded"> загрузка... </div>
            </div>

            <div class='mt-2 text-black text-center' >
                <div class="row ">
                    <div id="btnXReport" onclick="activate_btn('XReport')" class="col-3 btn btn-outline-dark textHover"> Получить X-отчёт </div>
                    <div class="col-1"></div>
                    <div onclick="activate_btn('cash')" class="col-4 btn btn-outline-dark textHover"> Внесение/Изъятие </div>
                    <div class="col-1"></div>
                    <div onclick="activate_btn('XCloseReport')" class="col-3 btn btn-outline-dark textHover"> Получить Z-отчёт </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let idKassa
        let idPassword
        let accountId = '{{ $accountId }}'
        idKassaCheck()

        function idKassaCheck(){
            idKassa = window.document.getElementById('idKassa').value
            idPassword = window.document.getElementById('idKassa').textContent

            let params = {
                znm: idPassword.trim(),
                password: idKassa,
            };

            let url = 'https://smartkaspi.kz/kassa/get_shift_report/info/'+accountId;
            //let url = 'https://rekassa/kassa/get_shift_report/info/'+accountId;


            let final = url + formatParams(params);

            console.log(final)

            const xmlHttpRequest = new XMLHttpRequest();
            xmlHttpRequest.addEventListener("load", function() {
                var json = JSON.parse(this.responseText);
                if (json.status == true){
                    window.document.getElementById('is_activated').innerText = 'Активна'
                    window.document.getElementById('is_activated').classList.add('bg-success')
                    window.document.getElementById('is_activated').classList.add('text-white')
                    window.document.getElementById('btnXReport').innerText = 'Получить Х-Отчёт'
                } else  {
                    window.document.getElementById('is_activated').classList.add('bg-danger')
                    window.document.getElementById('is_activated').innerText = "Смена закрыта"
                    window.document.getElementById('btnXReport').innerText = 'Получить последний отчёт'
                }
                if (json.code == 400){
                    window.document.getElementById('message').innerText = 'Ошибка 400, ' + json.message
                    window.document.getElementById('message').style.display = 'block'
                    window.document.getElementById('btnXReport').innerText = 'Получить последний отчёт'
                }

            });
            xmlHttpRequest.open("GET", final);
            xmlHttpRequest.send();

        }

        function saveValCash(){
            let inputSum = window.document.getElementById('inputSum').value

            let params = {
                znm: idPassword.trim(),
                password: idKassa,
                operations: window.document.getElementById('operations').value,
                sum:inputSum,
            };

            let url = 'https://smartkaspi.kz/kassa/change/'+accountId;
           // let url = 'https://rekassa/kassa/change/'+accountId;

            let final = url + formatParams(params);
            console.log(final)

            const xmlHttpRequest = new XMLHttpRequest();
            xmlHttpRequest.addEventListener("load", function() {
                let json = JSON.parse(this.responseText);
                if (json.status == true){

                    let message_good = window.document.getElementById('message_good');
                    message_good.style.display = 'block'
                    message_good.innerText = JSON.stringify(json.message_good)
                    closeModal('cash')
                } else if (json.status == false){

                    window.document.getElementById('message').style.display = 'block'
                    window.document.getElementById('message').innerText = JSON.stringify(json.message)
                    closeModal('cash')
                }
            });
            xmlHttpRequest.open("POST", final);
            xmlHttpRequest.send();

        }

        function formatParams(params) {
            return "?" + Object
                .keys(params)
                .map(function (key) {
                    return key + "=" + encodeURIComponent(params[key])
                })
                .join("&")
        }

        function responseZRReport(){
            idKassa = window.document.getElementById('idKassa').value
            idPassword = window.document.getElementById('idKassa').textContent
            let pin_code = window.document.getElementById('pin_code').value

            let params = {
                znm: idPassword.trim(),
                password: idKassa,
                pin_code:pin_code,
            };

            let url = 'https://smartkaspi.kz/kassa/get_close_report/'+accountId;
            //let url = 'https://rekassa/kassa/get_close_report/'+accountId;


            let final = url + formatParams(params);

            console.log(final)

            const xmlHttpRequest = new XMLHttpRequest();
            xmlHttpRequest.addEventListener("load", function() {
                var json = JSON.parse(this.responseText);
                console.log(json)
                if (json.status == true){
                    window.open(json.link)
                }else {
                    window.document.getElementById('message').innerText = JSON.stringify(json.message)
                    window.document.getElementById('message').style.display = 'block'
                }
            });
            xmlHttpRequest.open("POST", final);
            xmlHttpRequest.send();

        }

    </script>

    <div class="modal fade" id="cash" tabindex="-1"  role="dialog" aria-labelledby="cashTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cashTitle">Внесение</h5>
                    <div class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;"><i onclick="closeModal('cash')" class="fa-regular fa-circle-xmark"></i></div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="operations" class="col-5 col-form-label"> Выберите операцию </label>
                        <div class="col-7">
                            <select id="operations" name="operations" class="form-select text-black" onchange="valueCash(this.value)">
                                <option value="1"> Внесение </option>
                                <option value="2"> Изъятие </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-5 col-form-label">
                            <span id="inputGroupText" class="p-2 text-white bg-success rounded">Введите сумму </span>
                        </label>
                        <div class="col-7  mt-1">
                            <div class="input-group">
                                <input id="inputSum" name="inputSum" onkeypress="return isNumber(event)" type="text" class="form-control" aria-label="">
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="closeModal('cash')" type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button onclick="saveValCash()" type="button" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ZCloseReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input id="pin_code" type="number" onkeypress="return isNumber(event)" placeholder="PIN code"
                                   class="form-control float" required maxlength="10" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  onclick="responseZRReport()" id="closeShift" data-bs-dismiss="modal" class="btn btn-danger">Закрыть смену</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        function isNumber(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode == 46){
                var inputValue = $("#card").val();
                var count = (inputValue.match(/'.'/g) || []).length;
                if(count<1){
                    if (inputValue.indexOf('.') < 1){
                        return true;
                    }
                    return false;
                }else{
                    return false;
                }
            }
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
                return false;
            }
            return true;
        }

        function isNumberZ(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode == 46){
                var inputValue = $("#pin_code").val();
                var count = (inputValue.match(/'.'/g) || []).length;
                if(count<1){
                    if (inputValue.indexOf('.') < 1){
                        return true;
                    }
                    return false;
                }else{
                    return false;
                }
            }
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
                return false;
            }
            return true;
        }

        function activate_btn(params){
            if (params == 'cash'){
                $('#cash').modal('show')
                window.document.getElementById('inputSum').value = 0
            }
            if (params == 'XReport'){
                idKassa = window.document.getElementById('idKassa').value

                let params = {
                    znm: idPassword.trim(),
                    password: idKassa,
                };

                let url = 'https://smartkaspi.kz/kassa/get_shift_report/'+accountId;
                //let url = 'https://rekassa/kassa/get_shift_report/'+accountId;
                let final = url + formatParams(params);

                const xmlHttpRequest = new XMLHttpRequest()
                xmlHttpRequest.addEventListener("load", function() {
                    let json = JSON.parse(this.responseText)
                    window.open(json.link)
                })
                xmlHttpRequest.open("POST", final)
                xmlHttpRequest.send()
            }
            if (params == 'XCloseReport'){
                $('#ZCloseReport').modal('show')
                window.document.getElementById('pin_code').value = ''
            }
        }

        function closeModal(params) {
            if (params == 'cash'){
                $('#cash').modal('hide')
            }
        }

        function valueCash(val){

            if (val == 1 ) {
                window.document.getElementById('cashTitle').innerText = 'Внесение'
                document.getElementById('inputGroupText').classList.add('bg-success')
                document.getElementById('inputGroupText').classList.remove('bg-danger')
            }
            if (val == 2) {
                window.document.getElementById('cashTitle').innerText = 'Изъятие'
                document.getElementById('inputGroupText').classList.add('bg-danger')
                document.getElementById('inputGroupText').classList.remove('bg-success')
            }
        }

    </script>

    <style>
        .rekassa_gradient{
            /* background: rgb(145,0,253);
             background: linear-gradient(34deg, rgba(145,0,253,1) 0%, rgba(232,0,141,1) 100%);*/
            background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );
        }
    </style>

@endsection

