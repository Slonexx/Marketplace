@extends('layout')

@section('content')
    <form action=" {{  route('Check') }} " method="post">
        <br>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i> Чекаю
        </button>
    </form>

@endsection



