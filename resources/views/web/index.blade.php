@extends('layout')

@section('content')
    <form action=" {{  route('check') }} " method="get">
        <br>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i> Чекаю
        </button>
    </form>

    <br>

@endsection

