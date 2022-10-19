@extends('layout')

@section('content')

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">

        <div class="row gradient rounded p-2 pb-2">
            <div class="col-10">
                <div class="mx-2"> <img src="https://dev.smartkaspi.kz/KaspiLogo.png" width="35" height="35"  alt="">
                    <span class="text-white"> Настройки &#8594; дополнительные настройки </span>
                </div>
            </div>
        </div>

        @isset( $result )
            @if( $result['status'] == true)
                <div id="success" class="mt-2 alert alert-success alert-dismissible fade show in text-center "> {{ $result['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if( $result['status'] == false)
                    <div id="danger" class="mt-2 alert alert-danger alert-dismissible fade show in text-center "> {{ $result['message'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            @endif
        @endisset

        <form class="mt-3" action="/Setting/add/{{ $accountId }}?isAdmin={{ $isAdmin }}" method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <i class="fa-solid fa-list-check text-orange"></i> Дополнительные настройки в заказе
            <div class="mt-3 mx-2 row">
                <P class="col-sm-5 col-form-label"> Выберите канал продаж: </P>
                <div class="col-sm-7">
                    <select name="Saleschannel" class="form-select text-black " >
                        @if ($Saleschannel == "0")
                            <option value="0" selected>Не выбирать </option>
                        @else  <option value="{{$Saleschannel}}" selected> {{$Saleschannel}} </option>
                        <option value="0" >Не выбирать </option>
                        @endif
                        @foreach($Body_saleschannel as $Body_saleschannel_item)
                            @if ($Saleschannel != $Body_saleschannel_item->name)
                                <option value="{{ $Body_saleschannel_item->name }}"> {{ ($Body_saleschannel_item->name) }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-1 mx-2 row">
                <P class="col-sm-5 col-form-label"> Выберите проект:  </P>
                <div class="col-sm-7">
                    <select name="Project" class="form-select text-black " >
                        @if ($Project == "0")
                            <option value="0" selected>Не выбирать </option>
                        @else  <option value="{{$Project}}" selected> {{$Project}} </option>
                        <option value="0" >Не выбирать </option>
                        @endif
                        @foreach($Body_project as $Body_project_item)
                            @if ($Project != $Body_project_item->name)
                                <option value="{{ $Body_project_item->name}}"> {{ ($Body_project_item->name) }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>

            <div> <i class="fa-solid fa-list-check text-orange"></i> Сопоставьте статусы заказов покупателя в МойСклад:
                {{--Статус одобрен банком :--}}
                <div class="row">
                    <P class="col-sm-4 col-form-label">
                        <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover2"
                                data-toggle="popover" data-placement="right" data-trigger="focus"
                                data-content="Данный статус информирует продавца о том, что необходимо принять заказ в Kaspi"> </button>
                        Одобрен банком : </P>

                    <script> $('.myPopover2').popover(); </script>

                    <div class="col-sm-8 ">
                        <select name="APPROVED_BY_BANK" class="form-select text-black">
                            @if($APPROVED_BY_BANK == null)
                                <option selected>Статус МойСклад</option>
                            @else <option value="{{$APPROVED_BY_BANK}}" selected>{{$APPROVED_BY_BANK}}</option>
                            @endif
                            @foreach($Body_customerorder as $bodyItem => $dat)
                                @if($dat->name != $APPROVED_BY_BANK) <option value="{{ $dat->name }}"> {{ ($dat->name) }} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                {{--Статус принят на обработку продавцом :--}}
                <div class="row">
                    <P class="col-sm-4 col-form-label">
                        <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover3"
                                data-toggle="popover" data-placement="right" data-trigger="focus"
                                data-content="Данный статус информирует продавца о том, что заказ принят продавцом и его необходимо выдать покупателю"> </button>
                        Принят на обработку продавцом: </P>

                    <script> $('.myPopover3').popover(); </script>

                    <div class="col-sm-8 ">
                        <select name="ACCEPTED_BY_MERCHANT" class="form-select text-black">

                            @if($ACCEPTED_BY_MERCHANT == null)
                                <option selected>Статус МойСклад</option>
                            @else <option value="{{$ACCEPTED_BY_MERCHANT}}" selected>{{$ACCEPTED_BY_MERCHANT}}</option>
                            @endif
                            @foreach($Body_customerorder as $bodyItem => $dat)
                                @if($dat->name != $ACCEPTED_BY_MERCHANT) <option value="{{ $dat->name }}"> {{ ($dat->name) }} </option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                {{-- Статус завершён :--}}
                <div class="row">
                    <P class="col-sm-4 col-form-label">
                        <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover4"
                                data-toggle="popover" data-placement="right" data-trigger="focus"
                                data-content="Данный статус информирует продавца о том, что заказ уже завершён"> </button>
                        Завершён: </P>

                    <script> $('.myPopover4').popover(); </script>

                    <div class="col-sm-8 ">
                        <select name="COMPLETED" class="form-select text-black">

                            @if($COMPLETED == null)
                                <option selected>Статус МойСклад</option>
                            @else <option value="{{$COMPLETED}}" selected>{{$COMPLETED}}</option>
                            @endif
                            @foreach($Body_customerorder as $bodyItem => $dat)
                                @if($dat->name != $COMPLETED) <option value="{{ $dat->name }}"> {{ ($dat->name) }} </option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                {{--Статус отменён :--}}
                <div class="row">
                    <P class="col-sm-4 col-form-label">
                        <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover5"
                                data-toggle="popover" data-placement="right" data-trigger="focus"
                                data-content="Данный статус информирует об отмене заказа"> </button>
                        Отменён: </P>

                    <script> $('.myPopover5').popover(); </script>

                    <div class="col-sm-8 ">
                        <select name="CANCELLED" class="form-select text-black">

                            @if($CANCELLED == null)
                                <option selected>Статус МойСклад</option>
                            @else <option value="{{$CANCELLED}}" selected>{{$CANCELLED}}</option>
                            @endif
                            @foreach($Body_customerorder as $bodyItem => $dat)
                                @if($dat->name != $CANCELLED) <option value="{{ $dat->name }}"> {{ ($dat->name) }} </option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                {{--Статус Возврат--}}
                <div class="row">
                    <P class="col-sm-4 col-form-label">
                        <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover7"
                                data-toggle="popover" data-placement="right" data-trigger="focus"
                                data-content="Данный статус информирует о возврате товара"> </button>
                        Возвращён: </P>

                    <script> $('.myPopover7').popover(); </script>

                    <div class="col-sm-8 ">
                        <select name="RETURNED" class="form-select text-black">
                            @if($RETURNED == null)
                                <option selected>Статус МойСклад</option>
                            @else <option value="{{$RETURNED}}" selected>{{$RETURNED}}</option>
                            @endif
                            @foreach($Body_customerorder as $bodyItem => $dat)
                                @if($dat->name != $RETURNED) <option value="{{ $dat->name }}"> {{ ($dat->name) }} </option>
                                @endif
                            @endforeach

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
