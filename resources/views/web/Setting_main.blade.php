<?php session_start(); ?>
@extends('layout')

@section('content')

    <div class="content p-4 mt-2 bg-white text-Black rounded">
        <h2 align="center">Основные настройки <i class="fa-solid fa-gears text-orange"></i> </h2>
        <div class="">
            <p> Настройки интеграции <button type="button" data-bs-toggle="modal" data-bs-target="#modal"
                                             class="btn btn-new fa-solid fa-circle-info"> </button></p>



            <div class="row g-3 align-items-center">
                @if(Session::has('message'))
                <div class="alertheight">
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" role="alert">
                        {{ Session::get('message') }}
                    </div>
                </div>
                @endif
            <form action=" {{  route('Setting_Send') }} " method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <div class="mb-3 row">
                    <label for="TokenKaspi" class="col-sm-2 col-form-label" >Токен Kaspi-Api</label>
                    <div class="col-sm-10">
                        <input type="text" name="API_KEY" placeholder="Token-API ключ от Kaspi" id="TokenKaspi"
                               class="form-control" required maxlength="255"
                               value="<?php if(isset($_SESSION['API_KEY'])) {echo ($_SESSION["API_KEY"]); } else { echo "";} ?>">

                    </div>
                     </div>


            </form>
            </div>


                <hr class="href_padding">

                <div>
                    <P> Сопоставьте статусы платежей и заказов покупателя в МойСклад: </P>



                </div>
                <div class='d-flex justify-content-end text-black'>
                    <button type="submit" class="btn btn-outline-dark"> Сохранить </button>
                </div>




        </div>
    </div>




@endsection


<style>
    .text-orange{
        color: orange;
    }

    .content button:hover{
        color: orange;
    }

    .btn-new:hover{
       /* border:  1px double #ffffff;*/
        border-color: white !important
    }


</style>
