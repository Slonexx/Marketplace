@extends('layout')

@section('content')

    @php



    @endphp

    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <form action=" {{ route('Check', ['accountId' => $accountId] ) }} " method="get">
            <br>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-envelope"></i> SERGEI CHECK </button>
        </form>
    <div


@endsection

