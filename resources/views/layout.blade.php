<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>marketplace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">


</head>
<body>

    <div class="sidenav">
        <div class="toc-list-h1">
            <a href="/">Главное </a>
            <a href="/">Настройки </a>
            <a href="#about">Настройки </a>
            <a href="routes">Настройки </a>
        </div>
        <div class="toc-footer">
            <a href="/supportHelp">Написать нам</a>
        </div>
    </div>

    <div class="main">
    @yield('content')
    </div>
</body>
</html>



<style>
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #444444;
        font-size: 9pt;
        background-color: #FAFAFA;
    }

    .background{
        color: #e7e7e7;
    }

    .sidenav {
        height: 100%;
        width: 20%;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 20px;
        padding-left: 5px;
        padding-right: 5px;
    }

    .toc-footer a{
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 80px;
    }

    .sidenav a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
    }

    .sidenav a:hover {
        color: #f1f1f1;
    }

    .main {
        margin-top: 10px;
        margin-left: 21%; /* Same as the width of the sidenav */
        font-size: 28px; /* Increased text to enable scrolling */
        padding: 0 10px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }
</style>

