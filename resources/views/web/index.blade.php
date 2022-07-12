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

@endsection



