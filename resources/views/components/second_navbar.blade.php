<style>
    #navbarNav{
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #navbarNav a{
        font-size: 18px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="{{ url('/') }}">Acasa <span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('autentificate') }}">Autentificare</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('about') }}">Despre</a>
      </li>
    </ul>
  </div>
</nav>