@props([
    'id' => 'myCarousel',
    'image_one' => '',
    'image_two' => '',
    'image_three' => ''
])

<style>
    .carousel{
        position: relative;
    }
    .carousel_button{
        position: absolute;
        margin: auto;
        width: 200px;
        height: 200px;

    }
    .carousel-item {
        height: 32rem;
        background: #777;
        color: white;
        position: relative;
    }
    .carousel-item .container a{
        z-index: 5;
    }

    .carousel-item img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        filter:brightness(60%)
    }


    .container {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding-bottom: 50px;
    }
    
</style>

<div id="{{ $id }}" class="carousel slide col-12" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#{{ $id }}" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#{{ $id }}" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#{{ $id }}" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <!-- First Slide -->
        <div class="carousel-item active">
            <img src="{{ $image_one }}" class="d-block w-100" alt="Image 1">
            <div class="container">
                <h1>Ai nevoie de un loc bun pe perioada studentiei?</h1>
                <p>Centrul Vietii este un camin crestin, cu valori si principii crestine</p>
                <a href="{{ route('autentificate') }}" class="btn btn-lg btn-primary">Rezervă-ți loc</a>
            </div>
        </div>

        <!-- Second Slide -->
        <div class="carousel-item">
            <img src="{{ $image_two }}" class="d-block w-100" alt="Image 2">
            <div class="container">
                <h1>Esti curios cu ce ne ocupam?</h1>
                <p>Daca esti interesat sa afli cu ce ne ocupam, mergi la "despre noi" si afla mai multe</p>
                <a href="{{ route('about') }}" class="btn btn-lg btn-primary">Despre noi</a>
            </div>
        </div>

        <!-- Third Slide -->
        <div class="carousel-item">
            <img src="{{ $image_three }}" class="d-block w-100" alt="Image 3">
            <div class="container">
                <h1>Ai trimis cererea si ti-a fost acceptata?</h1>
                <p>Daca ai trimis o cerere de cazare si ti-a fost acceptata, intra pe signup si creaza-ti cont cu mailul de pe care ai trimis cererea</p>
                <a href="{{ route('autentificate') }}" class="btn btn-lg btn-primary">Autentifică-te</a>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev carousel_button" type="button" data-bs-target="#{{ $id }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next carousel_button" type="button" data-bs-target="#{{ $id }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>
