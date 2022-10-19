@extends('layout')

@section('content')

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">
        @if ( request()->isAdmin != null and request()->isAdmin != 'ALL' )
            <div class="mt-2 alert alert-danger alert-dismissible fade show in text-center  "> Доступ к настройкам есть только у администратора
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

            <div class="row rekassa_gradient rounded p-2 ">
                <div class="col-10">
                    <div class="mx-2"> <img src="https://app.rekassa.kz/static/logo.png" width="35" height="35"  alt="">
                        <span class="text-white" style="font-size: 20px"> Возможности интеграции </span>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6">
                    <div class="row">
                        <div> <strong>ФИСКАЛИЗАЦИЯ ПРОДАЖ</strong></div>
                        <div class="">
                            Можно фискализировать продажи из документов Заказ покупателя и Отгрузка с отправкой чека на WhatsApp или почту, также можно скачать или распечатать его.
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mt"> <strong>ФИСКАЛИЗАЦИЯ ВОЗВРАТОВ</strong></div>
                    <div class="">
                        Возврат можно произвести как из документов Заказ покупателя и Отгрузка, так и из Возврата покупателю.
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <div class="row">
                        <div class=""> <strong>РАБОТА С МАРКИРОВАННЫМИ ТОВАРАМИ</strong></div>
                        <div class="">
                            Наше решение позволяет отправлять коды маркировки в ОФД для списания с вашего виртуального склада.
                            Фискализация продаж маркированныйх товаров происходит только через документ Отгрузка.
                            Фискализация возвратов маркированныйх товаров происходит только через документ Возврат покупателю.
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class=""> <strong>АВТОМАТИЧЕСКОЕ СОЗДАНИЕ ДОКУМЕНТОВ</strong></div>
                    <div class="">
                        Вы можете упростить себе жизнь и настроить автоматическое создание Платежных документов (Ордера или Платежи) с выбором счета для Входящих/Исходящих платежей.
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <div class=""> <strong>7 ДНЕЙ БЕСПЛАТНО</strong></div>
                    <div class="">
                        Мы на 1000% уверены в своем приложении и поэтому готовы предоставить 7 дней, чтобы Вы могли оценить его возможности и уникальность.
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
@endsection


<style>
    .rekassa_gradient{
        /* background: rgb(145,0,253);
         background: linear-gradient(34deg, rgba(145,0,253,1) 0%, rgba(232,0,141,1) 100%);*/
        background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );
    }
</style>
