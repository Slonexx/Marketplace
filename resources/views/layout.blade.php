<!doctype html>
<html lang="en">
@include('head')
<body style="background-color:#dcdcdc;">

<div class="page">
        <div class="sidenav">
            <div class="p-2 gradient ">
                <div class="row text-white">
                    <div class="col-2">
                        <img src="https://dev.smartkaspi.kz/KaspiLogo.png" width="35" height="35"  alt="">
                    </div>
                    <div class="mt-1 col-10">
                        <label class="s-min-16 text-black"> Магазин Kaspi.kz </label>
                    </div>

                </div>
            </div> <br>
            <div class="toc-list-h1">
                <a class="mt-2 mb-2" href="/{{$accountId}}?isAdmin={{ request()->isAdmin }}">Главная </a>
                <a href="{{  route('Setting_Main', ['accountId' => $accountId] ) }}">Настройки </a>
                <a href="{{  route('ExportProduct', ['accountId' => $accountId] ) }}">Отправить товар </a>
                <a href="{{  route('InfoLog', ['accountId' => $accountId] ) }}">Логи</a>
            </div>
            <div class="">
                <button class="dropdown-btn">Помощь
                    <i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-container">
                        <a href={{  route('support', ['accountId' => $accountId] ) }}>
                            <i class="fa-solid fa-at"></i>
                            Написать на почту</a>
                        <a  href={{  route('whatsapp', ['accountId' => $accountId] ) }} >
                            <i class="fa-brands fa-whatsapp"></i>
                            Написать на WhatsApp </a>
                    </div>
            </div>

        </div>

        <div class="main">
                @yield('content')
        </div>
    </div>

</body>
</html>


<style>

    .head-full {
        height: 720px;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #444444;
        font-size: 8pt;
        background-color: #FAFAFA;
    }

    .s-min-16 {
        font-size: 16px;
    }

    .gradient{
        background-color: #FFE53B;
        background-image: linear-gradient(147deg, #FFE53B 0%, #FF2525 74%);
    }

    /* Фиксированный боковых навигационных ссылок, полной высоты */
    .sidenav {
        height: 100%;
        width: 15%;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #eaeaea;
        overflow-x: hidden;
        padding-top: 20px;
    }

    /* Стиль боковых навигационных ссылок и раскрывающейся кнопки */
    .sidenav a, .dropdown-btn {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 16px;
        color: #343434;
        display: block;
        border: none;
        background: none;
        width:100%;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    /* При наведении курсора мыши */
    .sidenav a:hover, .dropdown-btn:hover {
        background-image: linear-gradient(147deg, #FFE53B 0%, #FF2525 74%);
        border-radius: 10px 10px 0px 0px;
        color: #000000;
    }

    /* Основное содержание */
    .main {
        margin-left: 15%; /* То же, что и ширина боковой навигации */
        font-size: 18px; /* Увеличенный текст для включения прокрутки */
        padding: 0 10px;
    }
    /* Добавить активный класс для кнопки активного выпадающего списка */
    .active {
        background-image: linear-gradient(147deg, #FFE53B 0%, #FF2525 74%);
        margin-right: 50px;
        border-radius: 10px 10px 0px 0px;
        color: #000000;
    }

    /* Выпадающий контейнер (по умолчанию скрыт). Необязательно: добавьте более светлый цвет фона и некоторые левые отступы, чтобы изменить дизайн выпадающего содержимого */
    .dropdown-container {
        display: none;
        background-color: #d5d5d5;
        padding: 5px;
    }

    /* Необязательно: стиль курсора вниз значок */
    .fa-caret-down {
        float: right;
        padding-right: 8px;
    }
</style>

<style>
    /* Новый цвет текста */
    .text-orange{
        color: orange;
    }
    .transparent{
        border: none !important;
    }
</style>

<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

