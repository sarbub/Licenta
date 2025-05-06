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
        .main_validation_code_div {
            display: flex;
            flex-wrap: nowrap;
            width: 100vw;
            height: 100vh;
            justify-content: space-around;
            align-items: center;
        }

        .main_validation_code_div form {
            display: flex;
            flex-direction: column;
        }

        .main_validation_code_div form input {
            height: 90px;
            width: 70px;
            font-size: 50px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid var(--dark_gray);

        }

        .main_validation_code_div form div {
            width: 100%;
            align-items: center;
            justify-content: center;
        }

        .main_validation_code_div form button {
            width: 140px;
            margin: auto;
        }

        .main_validation_code_div section {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            margin: auto;
        }

        .is-invalid {
            border-color: red !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
        }
        #resend_code_form .d-flex{
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center!important;
        }
        #resend_code_form .d-flex p{
            margin: auto;
        }
    </style>

<body>

    <div class="main_validation_code_div">
        <section class="col-6">
            <h1>Crearea contului este simplu, rapid si usor</h1>
            <h2>CV-Platform se axeaza pe o experienta placuta</h2>
        </section>
        <section class="col-6">
            <form id="codeForm" method="POST" action="{{ route('verifyEmailCode.form') }}">
                @csrf
                @if ($errors->send_email_verification_code_error_bag->any())
                <div class="text-danger mb-3 lh-1">
                    <p>Câmpurile nu pot fi lăsate goale</p>
                    <p>Vă rugăm sa introduceți numai cifre</p>
                </div>
                @endif
                @if ($errors->has('code_verf_error'))
                <div class="alert alert-danger">
                    {{ $errors->first('code_verf_error') }}
                </div>
                @endif
                @if(session('code_sent_success'))
                <div class="alert alert-success">
                    {{ session('code_sent_success') }}
                </div>
                @endif
                <div class="d-flex gap-4">
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit1')) is-invalid @endif" name="digit1" inputmode="numeric" value="{{ old('digit1') }}" />
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit2')) is-invalid @endif" name="digit2" inputmode="numeric" value="{{ old('digit2') }}" />
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit3')) is-invalid @endif" name="digit3" inputmode="numeric" value="{{ old('digit3') }}" />
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit4')) is-invalid @endif" name="digit4" inputmode="numeric" value="{{ old('digit4') }}" />
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit5')) is-invalid @endif" name="digit5" inputmode="numeric" value="{{ old('digit5') }}" />
                    <input type="text" maxlength="1" class="code-input @if($errors->send_email_verification_code_error_bag->has('digit6')) is-invalid @endif" name="digit6" inputmode="numeric" value="{{ old('digit5') }}" />
                </div>

                <button type="submit" id="send_verification_code_btn" class="btn btn-primary mt-5">
                    <span id="sendText">Trimite cod</span>
                    <span id="sendSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
            <form method="POST" id="resend_code_form" class="mt-3" action="{{ route('verify_email_for_sending_request_controller.form') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('email_for_verification') }}">
                <div class="d-flex">
                    <p>Nu ai primit cod?</p>
                <button type="submit" id="resend_code_btn" class="btn btn-link" {{ session('email_for_verification') ? '' : 'disabled' }}>
                    <span id="sendText2">Retrimite cod</span>
                    <span id="sendSpinner2" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const inputs = document.querySelectorAll(".code-input");

            inputs.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    const value = e.target.value;
                    e.target.classList.remove("is-invalid");
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });
                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
    <script>
        const form = document.getElementById('codeForm');
        const button = document.getElementById('send_verification_code_btn');
        const spinner = document.getElementById('sendSpinner');
        const text = document.getElementById('sendText');

        form.addEventListener('submit', function() {
            button.disabled = true;
            spinner.classList.remove('d-none'); // show spinner
            text.textContent = 'Se trimite...';
        });
    </script>

<script>
        const form2 = document.getElementById('resend_code_form');
        const button2 = document.getElementById('resend_code_btn');
        const spinner2 = document.getElementById('sendSpinner2');
        const text2 = document.getElementById('sendText2');

        form2.addEventListener('submit', function() {
            button2.disabled = true;
            spinner2.classList.remove('d-none'); // show spinner
            text2.textContent = 'Se retrimite...';
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const inputs = document.querySelectorAll(".code-input");

            inputs.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    // Keep only digits
                    e.target.value = e.target.value.replace(/\D/g, '');

                    // Remove invalid class if user corrects input
                    e.target.classList.remove("is-invalid");

                    // Auto move to next input
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>