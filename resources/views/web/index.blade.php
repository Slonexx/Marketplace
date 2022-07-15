@extends('layout')

@section('content')
    <form action=" {{  route('Vendor') }} " method="get">
        <br>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i> Чекаю
        </button>
    </form>

    <br>


    <select class="selectpicker">
        <option data-icon="fa-solid fa-droplet text-orange">Ketchup</option>
    </select>


@endsection

