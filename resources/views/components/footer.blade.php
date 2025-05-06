<style>
    footer {
        overflow: hidden;
        width: 100vw;
        margin-top: 20px;
        width: 100%;
        bottom: 0;

    }

    footer section {
        padding: 40px;
        display: flex;

    }

    footer section div {
        margin-right: 60px;
    }

    footer section a {
        color: black;
        text-decoration: none;

    }
</style>

<!-- Footer -->
<footer class="text-center text-lg-start bg-body-tertiary text-muted">
    <!-- Section: Social media -->
    <section>
        <div class="">
            <h4>Connect</h4>
            <ul>
                <li><a href="#"> Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Twitter</a></li>

            </ul>
        </div>
        <div class="">
            <h4>Links</h4>
            <ul>
                <li><a href="{{ url('/') }}"> Home</a></li>
                <li><a href="{{ route('autentificate') }}"> Autentificare</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>

            </ul>
        </div>
        <div class="">
            <h4>Privacy</h4>
            <ul>
                <li><a href="{{ route('privacy_policy') }}"> Privacy policy</a></li>
                <li><a href="#">Coockies policy</a></li>

            </ul>
        </div>

        <div class="">
            <h4>Adresa</h4>
            <ul>
                <li><a target="_blank" href="{{ url('https://maps.app.goo.gl/TSLfJ3r8zz8Jr8e87')}}"> Str. Ioan Neglici 20 - Timisoara</a></li>
            </ul>
        </div>

        <div class="">
            <h4>Aplica pentru un loc</h4>
            <ul>
                <li><a href="{{ route('autentificate') }}"> Aplica</a></li>
            </ul>
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2025 Copyright:
        <a class="text-reset fw-bold" href="#">FirePageCorp</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->