@extends('layout')

@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <form action=" {{  route('check') }} " method="get">
            <br>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-envelope"></i> SERGEI CHECK </button>
        </form>

        <br>

        <div class="float-start">Float start on all viewport sizes</div><br>
        <div class="float-end">Float end on all viewport sizes</div><br>
        <div class="float-none">Don't float on all viewport sizes</div>
        <br>
        <div class="row col-sm-auto">
            <div class="alert alert-danger alert-dismissible fade show " role="alert">  dwadwawdawdaw
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>
        </div>

<div
@endsection

