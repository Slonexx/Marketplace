@extends('layout')

@section('content')
    <form action=" {{  route('check') }} " method="get">
        <br>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i> Чекаю
        </button>
    </form>

    <br>

    <select name="Selectmenu" class="evidence-select">
        <option value="1">Один</option>
        <option value="2">Два</option>
    </select>

    <div class="evidence-content">Один</div>
    <div class="evidence-content">Два</div>

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

    <select name="price">
        <option value="100">маленький</option>
        <option value="500">большой</option>
        <option value="100500">гигантский</option>
    </select>
    <div id="priceDisplay">100</div>

    <script>
        $('select[name="price"]').on('change', function(){
            $('#priceDisplay').html(this.value)
        })
    </script>


    <p><strong>2. Из JavaScript в PHP (метод GET):</strong></p>

    <script>

        var userName2 = 'Дмитрий';

    </script>

    <?php
    if (isset($_GET['organization']))
    {
        echo "Значение JavaScript-переменной: ". $_GET['organization'];
    }

    else
    {
        echo '<script type="text/javascript">';
        echo 'document.location.href="' . $_SERVER['REQUEST_URI'] . '?organization=" + userName2';
        echo '</script>';
        exit();
    }
    ?>


@endsection

