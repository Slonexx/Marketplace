@extends('layout')

@section('content')

    <div class="p-4 mx-1 mt-1 bg-white rounded py-3">
        <div class="row gradient rounded p-2 pb-2">
            <div class="col-10">
                <div class="mx-2"> <img src="https://smartkaspi.kz/KaspiLogo.png" width="35" height="35"  alt="">
                    <span class="text-black"> Настройки &#8594; отправка товаров через Excel </span>
                </div>
            </div>
        </div>
        <div class="row">
            <p class="mt-3">1. Выберите товар и поставьте галочку под пунктом &#34;Добавлять товар на Kaspi&#34;</p>
            <p>2. Перейдите в приложение Магазин Kaspi.kz</p>
            <p>3. В меню выберите &#34;Отправить товар&#34;</p>
            <p>4. Нажмите на кнопку &#34;Скачать Excel&#34;</p>
            <p>5. Откройте свой кабинет Kaspi продавца</p>
            <p>6. В меню выберите Товары→Загрузить прайс-лист</p>
            <p>7. В открывшемся окне выберите &#34;Загрузить файл вручную&#34; и вставьте ранее скаченный файл</p>
        </div>
        <form class="mt-3" action=" {{  route('ExcelProducts' , ['TokenMoySklad' => $TokenMoySklad] ) }} " method="get">
            <div class="row">
                <div class="col-sm-8">Количество товаров которые можно отправить в Магазин Kaspi: {{$Count}} </div>
                <div class="col-sm-4 d-flex justify-content-end text-black btnP">
                    <button type="submit" class="btn btn-outline-dark">Скачать Excel</button>
                </div>
            </div>
        </form>
    </div>

@endsection

