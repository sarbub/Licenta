<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>

    <title>Centrul Vieții</title>
    <link rel="stylesheet" href="{{ asset('/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/classes.css') }}">
</head>

<body>
    <x-carousel image_one="/images/TimisoaraImage.jpg" image_two="/images/Timisoara.jpg" image_three="/images/Timisoara3.jpg" />
    <x-navigation />
    @if (session('request_success'))
    <div class="alert alert-success">
        {{ session('request_success') }}
    </div>

    @endif
    <x-dismiss-alert strong="Doresti sa aplici pentru un loc in caminul Centrul Vietii?" warning="Te rog să accesezi pagina de autentificare și să aplici." />
    <x-card title="Misiunea noastră!" link="{{ route('about') }}" text="Centrul Vieții este un cămin creștin din Timișoara care oferă tinerilor cu posibilități materiale limitate cazare și masă la prețuri accesibile. În cantina noastră, pregătim zilnic mese sănătoase și gustoase, asigurându-ne că studenții au energia necesară pentru a-și atinge obiectivele" button_text="Learn more" />
    <!-- <x-card id="firstCard" title="Locul tau" text="Centrul Vieții este un cămin creștin din Timișoara care oferă tinerilor cu posibilități materiale limitate cazare și masă la prețuri accesibile. În cantina noastră, pregătim zilnic mese sănătoase și gustoase, asigurându-ne că studenții au energia necesară pentru a-și atinge obiectivele" button_text="Learn more"/> -->
    <x-card-slide-component />
    <x-gallery />
    <!-- <div class="forth_section">
            <div class="placesInDorm">
                <span>
                    <h2 class="h2Regular">Total locuri</h2>
                    <h2 class="Thirdnumbers h2Regular">40</h2>
                </span>
                <div class="v1"></div>
                <span>
                    <h2 class="h2Regular">Disponibile</h2>
                    <h2 class="Thirdnumbers h2Regular" id="leftPlaces">15</h2>
                </span>
            </div>
            <p id="placesUpdateDate" class="text_animation">20/10/2024</p>
        </div> -->
    <x-card link="{{ route('register') }}" title="Ai fost acceptat in camin?" btn_background="white" btn_text_color="#1C1C1C" color="#4570b5" textColor="white" text="Dacă ai cererea acceptată, iți poți creea cont pe platformă, cu mailul de pe care ai solicitat un loc în cămin, pentru a sta la curent cu regulile, obligațiile dar și drepturile tale" button_text="Crează cont" />
    <x-footer />
    <script type="module" src="./js/index_frontend.js"></script>
    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>