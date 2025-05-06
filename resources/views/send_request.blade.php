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
            <h1>Cererea dvs va fi trimisă centrului pentru evaluare</h1>
            <h2>Vă rugam sa nu trimiteți cererea de mai multe ori</h2>
            <h3>Cererea de cazare o veți primi pe adresa de mail ca o confirmarea a faptului
                ca a ajuns in sistemul nostru
            </h3>
        </section>
        <section class="col-6">
        <form class="p-4 border rounded shadow-sm" action="{{route('sendRequest.form')}}" method="POST" style="max-width: 500px; margin: auto;">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Prenume</label>
                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Introdu prenumele"
                            required
                            pattern="^[a-zA-ZăâîșțĂÂÎȘȚ]+$"
                            maxlength="255"
                            title="Permise doar litere și diacritice.">
                        @error('first_name', 'send_request_errors')
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
                        @error('last_name', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Vârstă</label>
                        <input type="number" name="age" class="form-control" id="age" placeholder="Introdu vârsta"
                            required
                            min=18
                            max=100
                            title="Permise doar numere.">
                        @error('age', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="collage" class="form-label">Universitatea</label>
                        <input type="text" name="collage" class="form-control" id="college" placeholder="Introdu numele universității"
                            required
                            pattern="^[a-zA-ZăâîșțĂÂÎȘȚ]+$"
                            maxlength="255"
                            title="Permise doar litere și diacritice.">
                        @error('collage', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Județ</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Introdu județul tău"
                            required
                            pattern="^[a-zA-ZăâîșțĂÂÎȘȚ]+$"
                            maxlength="255"
                            title="Permise doar litere și diacritice.">
                        @error('address', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="siblings" class="form-label">Număr frați/surori</label>
                        <input type="number" name="siblings" class="form-control" id="siblings" placeholder="Introdu numărul de frați/surori"
                            required
                            max=100
                            title="Permise doar numere.">
                        @error('siblings', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="income" class="form-label">Venit familie</label>
                        <input type="number" name="income" class="form-control" id="income" placeholder="Introdu venitul familiei"
                            required
                            max=100
                            title="Permise doar numere."
                            step="any">
                        @error('income', 'send_request_errors')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <button type="submit" class="btn btn-primary w-100">Trimite cerere</button>
                </form>
        </section>
    </div>
    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
</div>