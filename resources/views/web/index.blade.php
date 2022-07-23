@extends('layout')

@section('content')

    @php



    @endphp



    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <h1 class=" text-black">  <i class="fas fa-book-open text-orange"></i> Инструкция</h1>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-black">
                        <p> 1)dwadwa</p>

                    </div>
                    <div class="col-sm-6">
                        <div class="embed-responsive embed-responsive-16by9 ">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/iBlyGEGOPcI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>

                </div>
            </div>
    </div>




    <form action=" {{ route('Check', ['accountId' => $accountId] ) }} " method="get">
               <br>
               <button type="submit" class="btn btn-primary"><i class="fa-solid fa-envelope"></i> SERGEI CHECK </button>
    </form>

@endsection

