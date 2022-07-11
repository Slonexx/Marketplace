

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>marketplace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body style="background-color:#dcdcdc;">

<div class="page">
        <div class="sidenav">



            <div class="toc-list-h1">
                <a href="/">Главное </a>
                <button class="dropdown-btn">Настройки<i class="fa fa-caret-down"></i></button>
                <div class="dropdown-container">
                    <a href={{  route('Setting_Main') }}>Основная настройка</a>
                    <a href="#">Ссылка 1</a>
                    <a href="#">Ссылка 2</a>
                </div>
                <hr class="">
            </div>
            <div class="">
                <button class="dropdown-btn">Помощь
                    <i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-container">
                        <a href={{  route('support') }}>

                            Написать на почту</a>

                        <a href={{  route('whatsapp') }}>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/WhatsApp_logo-color-vertical.svg/2048px-WhatsApp_logo-color-vertical.svg.png" width="20" height="20" alt="">
                            Написать в WhatsApp </a>
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
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #444444;
        font-size: 8pt;
        background-color: #FAFAFA;
    }

    .alertheight{
        text-align: center;
        float: right;
        position: relative;
        margin-right: auto;
        width: 25%;
    }

    /* Фиксированный боковых навигационных ссылок, полной высоты */
    .sidenav {
        height: 100%;
        width: 200px;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 20px;
    }

    /* Стиль боковых навигационных ссылок и раскрывающейся кнопки */
    .sidenav a, .dropdown-btn {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 16px;
        color: #bebebe;
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
        background-color: #ffffff;
        border-radius: 20px;
        color: #EEA200;
    }

    /* Основное содержание */
    .main {
        margin-left: 200px; /* То же, что и ширина боковой навигации */
        font-size: 20px; /* Увеличенный текст для включения прокрутки */
        padding: 0px 10px;
    }
    /* Добавить активный класс для кнопки активного выпадающего списка */
    .active {
        background-color: #eeeeee;
        padding-right: -20px;
        padding-left: -20px;
        border-radius: 5px;
        color: #e59300;
    }

    /* Выпадающий контейнер (по умолчанию скрыт). Необязательно: добавьте более светлый цвет фона и некоторые левые отступы, чтобы изменить дизайн выпадающего содержимого */
    .dropdown-container {
        display: none;
        background-color: #262626;
        padding-left: 8px;
    }

    /* Необязательно: стиль курсора вниз значок */
    .fa-caret-down {
        float: right;
        padding-right: 8px;
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

