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
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <button type="button"
            class="btn btn-warning myPopover"
            data-toggle="popover"
            data-placement="right" title="Dismissiabe Popover"
            data-trigger="focus"
            data-content="I display when the button is focused!">Focus Me</button>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


    <script>
        $('.myPopover').popover();
    </script>


@endsection

