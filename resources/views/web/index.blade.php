@extends('layout')
@section('item', 'link_1')
@section('content')
    <div class="content p-4 mt-2 bg-white text-Black rounded">

        <div class="row gradient rounded p-2 pb-2" style="margin-top: -1rem">
            <div class="col-10" style="margin-top: 1.2rem"> <span class="text-white" style="font-size: 20px">  Возможности интеграции </span> </div>
            <div class="col-2 text-center">
                <img src="https://smarttis.kz/Config/logo.png" width="40%"  alt="">
                <div class="text-white" style="font-size: 11px; margin-top: 8px"> Топ партнёр сервиса МойСклад </div>
            </div>
        </div>

        @if ( request()->isAdmin != null and request()->isAdmin != 'ALL' )
            <div class="mt-2 alert alert-danger alert-dismissible fade show in text-center  "> Доступ к настройкам есть только у администратора
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-6">
                <div class="row">
                    <div> <strong>ПОЛУЧЕНИЕ ЗАКАЗОВ ИЗ KASPI</strong></div>
                    <div class="">
                        Заказы поступают в МойСклад автоматически, вы получаете уведомление. Если поменяется Статус заказа в Kaspi, то вы это сразу увидите в МоемСкладе. Вы можете настроить Статусы самостоятельно.
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="mt"> <strong>ЗАГРУЗКА ТОВАРОВ ИЗ/В KASPI</strong></div>
                <div class="">
                    При поступлении заказа Товары будут проверяться в МоемСкладе по артикулу и/или названию и создаваться в случае отсутствия. Также вы можете выгрузить товары из МоегоСклада в Kaspi в 2 клика
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <div class="row">
                    <div class=""> <strong>ПОЛУЧЕНИЕ ДАННЫХ О КЛИЕНТЕ</strong></div>
                    <div class="">
                        Клиенты из Kaspi проверяются в МоемСкладе по номеру телефона. В случае отсутствия клиента в базе, он создастся автоматически как Физическое лицо.
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class=""> <strong>АВТОМАТИЧЕСКОЕ СОЗДАНИЕ ДОКУМЕНТОВ</strong></div>
                <div class="">
                    Вы можете автоматизировать продажи и настроить автоматическое создание Платежных документов, Отгрузок, Счетов-фактур и Возвратов.
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <div class=""> <strong>14 ДНЕЙ БЕСПЛАТНО</strong></div>
                <div class="">
                    Мы на 1000% уверены в своем приложении и поэтому готовы предоставить 14 дней, чтобы Вы могли оценить его возможности и уникальность.
                </div>
            </div>
            <div class="col-6">
                <div class=""> <strong>НОВЫЕ ВОЗМОЖНОСТИ</strong></div>
                <div class="">
                    Мы не стоим на месте, поэтому совсем скоро Вы сможете оценить новые фишки в нашем приложении. Ну и будем признатальны за обратную связь.
                </div>
            </div>
        </div>
    </div>

    <style>
        .mb-0-all p{
            margin-bottom: 2px !important;
        }
    </style>

@endsection

