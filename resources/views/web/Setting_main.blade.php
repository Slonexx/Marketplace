<?php session_start(); ?>
@extends('layout')

@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <h2 align="center">Основные настройки <i class="fa-solid fa-gears text-orange"></i> </h2>
        <div>
            <p> Настройки интеграции <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover"
                                             data-toggle="popover" data-placement="right" data-trigger="focus"
                                             data-content="Заполните обязательные поля, чтобы начать использование интеграции!"
                > </button></p>
            <script>
                $('.myPopover').popover();
            </script>
        </div>





        <div class="row g-3 align-items-center">
            @include('alerts')<br>

               {{-- @if(Session::has('message'))
                    <div class="alert text-center">
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }}  alert-dismissible fade show "
                             role="alert"> {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <br>
                        </div>
                    </div>
                @endif--}}
        </div>




            <form action=" {{  route('Setting_Send') }} " method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <div class="mb-3 row">
                    <label for="TokenKaspi" class="col-sm-2 col-form-label"> <i class="text-danger">*</i> Токен Kaspi-Api</label>
                    <div class="col-sm-10">
                        <input type="text" name="API_KEY" placeholder="Token-API ключ от Kaspi" id="TokenKaspi" class="form-control form-control-orange"
                               required maxlength="255" value="<?php if(isset($_SESSION['API_KEY'])) {echo ($_SESSION["API_KEY"]); } else { echo "";} ?>">
                    </div>
                </div>



                <hr class="href_padding">


                <div class="mb-3 row">
                    <p>Настройка документов</p>

                    <div class="mb-3 row">
                        <P class="col-sm-5 col-form-label"> Выберите на какую организацию создавать заказы: </P>
                        <div class="col-sm-7">
                            <select name="organization"  id="parent_id" class="form-select text-black dynamic" data-dependent="details">
                                <option selected ></option> <?php $value = 0; ?>
                                @foreach($Body_organization as $bodyItem => $dat)
                                    <option value="{{ $dat->id }}"> {{ ($dat->name) }} </option> <?php $value++; ?>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <P class="col-sm-5 col-form-label"> Выберите какой тип документа создавать: </P>
                        <div class="col-sm-7">
                            <select name="document" class="form-select text-black evidence-select" >
                                <option value="1">Приходной ордер (нету счёта) </option>
                                <option value="2">Входящий платёж </option>
                            </select>
                        </div>
                    </div>

                        {{--Выберите расчетный счет--}}
                        <div class="evidence-content"></div>
                        <div class="mb-3 row evidence-content" >
                            <P class="col-sm-5 col-form-label"> Выберите расчетный счет: </P>
                            <div class="col-sm-7">
                                @foreach($Body_organization as $row)
                                    <div class="some"  id="some_{{  $row->id }}"  style="display:none;">
                                        @php
                                            $id = $row->id;
                                            $array_element = [];
                                            $apiKey = "8eb0e2e3fc1f31effe56829d5fdf60444d2e3d3f"; // Пока что так
                                            $url_accounts = "https://online.moysklad.ru/api/remap/1.2/entity/organization/".$id."/accounts";
                                            $clinet = new \App\Http\Controllers\ApiClientMC($url_accounts, $apiKey);
                                            $Body_accounts = $clinet->requestGet()->rows;

                                            if (array_key_exists(0, $Body_accounts)) {
                                                foreach ($Body_accounts as $item) { array_push($array_element, $item->accountNumber); } }
                                            else { $array_element = [ 0 => "Нету Расчетного счета"];
                                            }
                                        @endphp
                                        <select name="payment" class="form-select text-black">
                                            <option selected></option>
                                            @foreach ($array_element as $array_element_item)
                                                <option value="{{$array_element_item}}"> {{ $array_element_item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    <script>
                        const selector = $('.evidence-select');
                        function update() {
                            const value = selector.val();
                            const theIndex = parseInt(value) - 1;
                            $('.evidence-content').each(function(index, el) {
                                $(el)[index === theIndex ? 'show' : 'hide']();
                            });
                        };
                        selector.on('click', update);
                        update();
                    </script>

                    <script type="text/javascript">
                        $('#parent_id').on('change',function(){
                            $(".some").hide();
                            var some = $(this).find('option:selected').val();
                            $("#some_" + some).show();}); </script>

                </div>







                <div>
                    <div class="mb-3 row">
                        <p class="col-sm-4 col-form-label">
                            <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover6"
                            data-toggle="popover" data-placement="right" data-trigger="focus"
                            data-content="По умолчанию идёт проверка по артикулу, вы можете убрать или же добавить еще проверки для добавлений товаров с kaspi в мой склад"></button>
                            Выберите способ проверки товаров: </p>

                        <script> $('.myPopover6').popover(); </script>

                        <div class="ms-4 form-check col-sm-3 col-form-label">
                            <input name="CheckArticle" class="form-check-input" type="checkbox" value="Article" id="flexCheckChecked" checked>
                            <label class="form-check-label" for="flexCheckChecked">проверять по Артикулу </label>
                        </div>
                        <div class="form-check col-sm-4 col-form-label">
                            <input name="CheckName"CheckName class="form-check-input" type="checkbox" value="Name" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault"> проверять по Названию </label>
                        </div>
                    </div>
                </div>



                <hr class="href_padding">



                <div>Сопоставьте статусы платежей и заказов покупателя в МойСклад:
                    {{--Статус одобрен банком :--}}
                    <div class="mb-3 row pt-3">
                        <P class="col-sm-4 col-form-label">
                            <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover2"
                                    data-toggle="popover" data-placement="right" data-trigger="focus"
                                    data-content="Данный статус информирует продавца о том, что необходимо принять заказ в kaspi"> </button>
                            Статус одобрен банком : </P>

                        <script> $('.myPopover2').popover(); </script>

                        <div class="col-sm-8 ">
                            <select name="APPROVED_BY_BANK" class="form-select text-black">
                                <?php $i = 0; ?> <option selected>Статус</option>
                                @foreach($Body as $bodyItem => $dat)
                                        <option data-icon="fa-solid fa-square-full" style="color: {{ $setBackground[$i] }}" <?php $i++;?>
                                                value="{{ $dat->name }}"> {{ ($dat->name) }}
                                        </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--Статус принят на обработку продавцом :--}}
                    <div class="mb-3 row">
                        <P class="col-sm-4 col-form-label">
                            <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover3"
                                    data-toggle="popover" data-placement="right" data-trigger="focus"
                                    data-content="Данный статус информирует продавца о том, что заказ принят продавцом и его необходимо отдать заказчику"> </button>
                            Статус принят на обработку продавцом: </P>

                        <script> $('.myPopover3').popover(); </script>

                        <div class="col-sm-8 ">
                            <select name="ACCEPTED_BY_MERCHANT" class="form-select text-black">
                                <?php $i = 0; ?> <option selected>Статус</option>
                                @foreach($Body as $bodyItem => $dat)
                                    <option data-icon="fa-solid fa-square-full" style="color: {{ $setBackground[$i] }}" <?php $i++;?>
                                    value="{{ $dat->name }}"> {{ ($dat->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Статус завершён :--}}
                    <div class="mb-3 row">
                        <P class="col-sm-4 col-form-label">
                            <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover4"
                                    data-toggle="popover" data-placement="right" data-trigger="focus"
                                    data-content="Данный статус информирует продавца о том, что заказ уже завершён"> </button>
                            Статус завершён: </P>

                        <script> $('.myPopover4').popover(); </script>

                        <div class="col-sm-8 ">
                            <select name="COMPLETED" class="form-select text-black">
                                <?php $i = 0; ?> <option selected>Статус</option>
                                @foreach($Body as $bodyItem => $dat)
                                    <option data-icon="fa-solid fa-square-full" style="color: {{ $setBackground[$i] }}" <?php $i++;?>
                                    value="{{ $dat->name }}"> {{ ($dat->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--Статус отменён :--}}
                    <div class="mb-3 row">
                        <P class="col-sm-4 col-form-label">
                            <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover5"
                                    data-toggle="popover" data-placement="right" data-trigger="focus"
                                    data-content="Данный статус информирует продавца о том, что заказ был отменён"> </button>
                            Статус отменён: </P>

                        <script> $('.myPopover5').popover(); </script>

                        <div class="col-sm-8 ">
                            <select name="CANCELLED" class="form-select text-black">
                                <?php $i = 0; ?> <option selected>Статус</option>
                                @foreach($Body as $bodyItem => $dat)
                                    <option data-icon="fa-solid fa-square-full" style="color: {{ $setBackground[$i] }}" <?php $i++;?>
                                    value="{{ $dat->name }}"> {{ ($dat->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>



                <hr class="href_padding">


                <div class='d-flex justify-content-end text-black btnP' >
                    <p class="btn btn-outline-dark textHover" data-bs-toggle="modal" data-bs-target="#modal">
                     <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </p>
                </div>
                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"> Вопрос <i class="fa-solid fa-circle-question text-danger"></i></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Вы уверены, что хотите сохранить настройки интеграции ? </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-outline-success">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </div>

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


</style>
