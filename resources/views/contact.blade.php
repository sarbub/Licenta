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
    <link rel="stylesheet" href="{{ asset('/css/contact.css') }}">
</head>

<body>
    <x-carousel image_one="/images/TimisoaraImage.jpg" image_two="/images/Timisoara.jpg" image_three="/images/Timisoara3.jpg" />
    <x-navigation />
    @if (session('contact_success'))
    <div class="alert alert-success">
        {{ session('contact_success') }}
    </div>
    @endif
    <x-well-done-box-component heading="Ai intrebari sau curiozitati ?" message="Nu ezita sa ne contactezi pe mail sau apeland la numarul de telefon" aditional="Numar de telefon: 0738476561" />
    <x-card title="Ai vrea sa aplici pentru un loc in camin si nu sti cum ?" button_text="Aplică aici !" link="{{ route('autentificate') }}" text="Daca doresti sa aplici pentru un loc in camin, te rog sa faci click butonului de mai jos" />
    <div class="contact_form_div">

        <form class="contact_form" method="post" id="contact_form" action="{{ route('contactForm.form') }}">
            @csrf
            <h2>Contact form</h2>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email" required maxlength="225">
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Mesajul dvs.</label>
                @error('bio')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <textarea name="bio" placeholder="Mesajul dvs." class="form-control custom-textarea" rows="3" required minlength="8" maxlength="225">{{ old('textArea') }}</textarea>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 submit_button">
                    <button type="submit" id="submit_form_btn" class="btn btn-primary">
                        <span id="sendText">Trimite mesaj</span>
                        <span id="sendSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <x-footer />
    <script>
        const form = document.getElementById('contact_form');
        const button = document.getElementById('submit_form_btn');
        const spinner = document.getElementById('sendSpinner');
        const text = document.getElementById('sendText');

        form.addEventListener('submit', function() {
            button.disabled = true;
            spinner.classList.remove('d-none'); // show spinner
            text.textContent = 'Se trimite...';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>