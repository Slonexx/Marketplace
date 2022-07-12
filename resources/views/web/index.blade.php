@extends('layout')

@section('content')
    <form action=" {{  route('Vendor') }} " method="get">
        <br>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i> Чекаю
        </button>
    </form>

    <h1 class="text-3xl font-bold underline">
        Hello world!
    </h1>

    <br>


    <div class="container py-3 text-center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
            Открыть модальное окно
        </button>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Информация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="img-fluid" src="/examples/images/admin-dashboard.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt vero illo error eveniet cum.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Да, хочу</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет, спасибо</button>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>


</script>

