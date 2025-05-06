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

</head>

<body>

    @error('email', 'narator_error_bag')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    @if (session('AdminUserCreated'))
    <div class="alert alert-success">
        {{ session('AdminUserCreated') }}
    </div>
    @endif
    @if (session('ModeratorUserCreated'))
    <div class="alert alert-success">
        {{ session('ModeratorUserCreated') }}
    </div>
    @endif




    <form method="post" id="naratorValidationEmailForm" class="p-4 border rounded shadow-sm w-50 d-flex flex-column gap-4" action="{{ route('ModeratorEmailValidation.form') }}">
        @csrf
        <label for="naratorValidationEmail" class="form-label">Narator</label>
        <input type="email" placeholder="enter email" class="form-control" name="email">
        <button id="validateNaratorButton" class="btn btn-primary">
            validate
        </button>
    </form>




    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>