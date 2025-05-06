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
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/classes.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=folded_hands" />

    <style>
        .main_autentificate {
            width: 80vw;
            margin: auto;
        }

        .autentificate_text_message section {
            display: flex !important;
            flex-direction: column;
            text-align: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        .folded_hands {
            font-size: 120px;
            color: var(--black);
        }

        .verify_email_div {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: nowrap;
        }

        .second_div img {
            width: 220px;
            height: 50px;
        }

        .alert_1_div {
            height: 100% !important;
        }

        .row {
            justify-content: center;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <x-carousel image_one="/images/TimisoaraImage.jpg" image_two="/images/Timisoara.jpg" image_three="/images/Timisoara3.jpg" />
    <x-navigation />
    @if (session('request_success'))
    <div class="alert alert-success">
        {{ session('request_success') }}
    </div>
    @endif
    <div class="main_autentificate col-12 p-3">
        <div class="row">
            <div class="col-6 col-md-6 col-lg-6 col-sm-12">
                <p class="fs-4">Autentificare</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Autentificare</a>
            </div>
            <div class="col-6 col-md-6 col-lg-6 col-sm-12 mb-5 h-100 alert_1_div">
                <div class="alert alert-warning warning_text1">
                    <b>Doar utilizatori cu cereri acceptate iși pot crea cont</b>
                    <p>Depune cererea de cazare, așteapă raspunsul, daca este da, revino</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-5 verify_email_div">
            <div class="col-6 col-sm-12 col-md-6 col-lg-6 autentificate_text_message">
                <section>
                    <span class="material-symbols-outlined folded_hands">
                        folded_hands
                    </span>
                    <p class="fs-2">Ești pregătit să completezi cererea de cazare?</p>
                    <p class="fs-5">Centrul Vieții este un loc potrivit pentru tinerii care îl caută pe Dumnezeu și doresc o relație autentică cu El</p>
                    <p class="fs-5">Este locul în care ai și timpul și liniștea necesară să înveți dar în același timp și posibilitatea de a lega
                        prietenii pe viață
                    </p>
                </section>
            </div>
            <div class="col-6 col-sm-12 col-md-6 col-lg-6 second_div">
                <h2>Verifica email inaninte de a trimite cererea</h2>
                <form method="POST" id="verify_email_for_sending_request_form" action="{{ route('verify_email_for_sending_request_controller.form') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        @error('email', 'verify_email_send_request_erorr_bag')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                            required
                            maxlength="255"
                            title="Te rugam sa adaugi un email valid">
                    </div>
                    <button type="submit" id="verify_email_for_sending_request_form_btn" class="btn btn-primary">
                        <span id="sendText2">Verifică</span>
                        <span id="sendSpinner2" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>
                <!-- <img src="{{ asset('images/config/a1.png') }}" class="mt-4" alt=""> -->
            </div>
        </div>
    </div>

    <x-footer />
    @if (session('scrollToForm'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verify_email_for_sending_request_form');
            form.scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>
    @endif
    <script>
        const form = document.getElementById('loginForm');
        const button = document.getElementById('loginSubmitButton');
        const spinner = document.getElementById('sendSpinner');
        const text = document.getElementById('sendText');

        form.addEventListener('submit', function() {
            button.disabled = true;
            spinner.classList.remove('d-none'); // show spinner
            text.textContent = 'Autentificare...';
        });
    </script>

<script>
        const form2 = document.getElementById('verify_email_for_sending_request_form');
        const button2 = document.getElementById('verify_email_for_sending_request_form_btn');
        const spinner2 = document.getElementById('sendSpinner2');
        const text2 = document.getElementById('sendText2');

        form2.addEventListener('submit', function() {
            button2.disabled = true;
            spinner2.classList.remove('d-none'); // show spinner
            text2.textContent = 'Verifică...';
        });
    </script>




    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>