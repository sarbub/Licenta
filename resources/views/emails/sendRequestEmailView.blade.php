<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Cerere Cazare Cămin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 30px;
        }

        .container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        h2 {
            color: #444;
        }

        .info {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cerere pentru cazare în cămin</h2>

        <p>Stimate Comitet de Cazare,</p>

        <p>Subsemnatul(a) solicit cazare în cadrul căminului studențesc, în baza informațiilor de mai jos:</p>

        <div class="info"><span class="label">Nume:</span> {{ $nume }}</div>
        <div class="info"><span class="label">Prenume:</span> {{ $prenume }}</div>
        <div class="info"><span class="label">Vârstă:</span> {{ $varsta }}</div>
        <div class="info"><span class="label">Universitate:</span> {{ $universitate }}</div>
        <div class="info"><span class="label">Județ:</span> {{ $judet }}</div>
        <div class="info"><span class="label">Număr frați/surori:</span> {{ $numarFrati }}</div>
        <div class="info"><span class="label">Venit lunar al familiei:</span> {{ $venitFamilie }} lei</div>

        <p>Vă rog să luați în considerare această solicitare. Vă mulțumesc anticipat pentru timpul acordat și pentru sprijin.</p>

        <p>Cu respect,<br>
        {{ $prenume }} {{ $nume }}</p>
    </div>
</body>
</html>

