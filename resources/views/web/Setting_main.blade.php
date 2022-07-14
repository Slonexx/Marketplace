<?php session_start(); ?>
@extends('layout')

@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <h2 align="center">Основные настройки <i class="fa-solid fa-gears text-orange"></i> </h2>
        <div class="">
            <p> Настройки интеграции <button type="button" class="btn btn-new fa-solid fa-circle-info myPopover"
                                             data-toggle="popover" data-placement="right" data-trigger="focus"
                                             data-content="Заполните обязательные поля, чтобы начать использование интеграции!"
                > </button></p>
            <script>
                $('.myPopover').popover();
            </script>

            <div class="row g-3 align-items-center">
                @if(Session::has('message'))
                <div class="alertheight">
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" role="alert">
                        {{ Session::get('message') }}
                    </div>
                </div>
                @endif
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

                <div>
                    <P> Сопоставьте статусы платежей и заказов покупателя в МойСклад: </P>
                    <div class="mb-3 row">
                        <P class="col-sm-4 col-form-label"> Статус на принятие продавцом: </P>
                        <div class="col-sm-auto dropdown">
                            <select class="form-select" data-show-content="true" name="value" aria-label="Статус">
                                <?php
                                $i = 0;
                                ?>

                                <option selected>Статус</option>
                                @foreach($Body as $bodyItem)
                                        <option style="background-color: {{ $setBackground[$i] }} "
                                                value="<?php $i++?>"> {{ ($bodyItem->name) }} </option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                </div>


                <div class='d-flex justify-content-end text-black btnP' >
                    <p class="btn btn-outline-dark textHover" data-bs-toggle="modal" data-bs-target="#modal"> <i class="fa-solid fa-arrow-down-to-arc"></i> Сохранить </p>
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








        </div>
    </div>




@endsection


<style>
 .new{
     color: blue;
     background-color: ;
 }
</style>
