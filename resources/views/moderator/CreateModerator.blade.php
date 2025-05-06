<!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->



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
        .main_send_request_div {
            display: flex;
            flex-wrap: nowrap;
            width: 100vw;
            height: 100vh;
            justify-content: space-around;
            align-items: center;
        }
    </style>

<body>

    <div class="main_send_request_div">
        <section class="col-6 d-flex flex-column text-center">
            <h1>Crearea contului de Admin/Narator</h1>
            <h2>Informațiile dvs vor fi securizate cu atentie sporită</h2>
        </section>
        <section class="col-6">
            @if (session('createAdminModeratorAccountError'))
            <div class="alert alert-danger">
                {{ session('createAdminModeratorAccountError') }}
            </div>
            @endif
            <form class="p-4 border rounded shadow-sm" action="{{ route('ModeratorUser.form') }}" method="POST" style="max-width: 500px; margin: auto;">
                @csrf
                <div class="mb-3">
                    <label for="first_name" class="form-label">Prenume</label>
                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Introdu prenumele"
                        required
                        pattern="^[a-zA-ZăâîșțĂÂÎȘȚ]+$"
                        maxlength="255"
                        title="Permise doar litere și diacritice.">
                    @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Nume</label>
                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Introdu numele de familie"
                        required
                        pattern="^[a-zA-ZăâîșțĂÂÎȘȚ]+$"
                        maxlength="255"
                        title="Permise doar litere și diacritice.">
                    @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Parolă</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Introdu parola"
                        required
                        minlength="8"
                        maxlength="255"
                        title="Parola trebuie să conțină minim 8 caractere.">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmă Parola</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirmă parola"
                        required
                        minlength="8"
                        maxlength="255">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="togglePassword">
                    <label class="form-check-label" for="togglePassword">
                        Afișează parola
                    </label>
                </div>


                <button type="submit" class="btn btn-primary w-100">Trimite cerere</button>
            </form>
        </section>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');

            const type = this.checked ? 'text' : 'password';
            passwordField.type = type;
            confirmPasswordField.type = type;
        });
    </script>
    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
</div>