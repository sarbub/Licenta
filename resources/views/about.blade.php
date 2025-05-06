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
    <style>
        .cantina_noastra_section, .camerele_studentiilor{
            margin: auto;
            border-radius: 10px;
            width: 70%;
        }
    </style>
</head>
<body>
    <x-carousel image_one="/images/TimisoaraImage.jpg" image_two="/images/Timisoara.jpg" image_three="/images/Timisoara3.jpg" />
    <x-navigation/>
    <x-card display="none" title="Inceputul nostru" text="Într-o mică încăpere, încălzită doar de căldura inimilor lor, au pus bazele a ceea ce avea să devină Fundația Umanitară Centrul Vieții. Cu resurse limitate, dar cu o determinare infinită, au început să ofere ajutor celor aflați în nevoie. Au organizat colecte de haine și alimente, au vizitat bătrâni singuri, au oferit consiliere psihologică și au încercat să aducă un zâmbet pe fețele celor care sufereau."/>
    
    <x-card display="none" color="white" title="Cantina noastra" text="In caminul Centrul Vieții, tinerii se pot bucura de luni pana vineri de 2 mese gustoase pe zi, pentur a le da energia de care au nevoie"/>

    <div class="cantina_noastra_section">
        <x-gallery/>
    </div>
    <x-card color="white" display="none" title="Camerele studențiilor" text="In căminul nostru, camerele sunt de două persoane, si o baie proprie la fiecare cameră, oferind tinerilor locul necesar pentru a duce o viață decentă in centru"/>
    <div class="camerele_studentiilor">
        <x-gallery/>
    </div>
    <x-card color="white" display="none" title="Timpul de părtășie" text="In fiecare marți la centrul Vieții ne bucurăm de un timp în prezența Domnului, alături de invitați speciali, prin care Dumnezeu ne vorbește"/>
    <div class="timpul_de_lauda">
        <x-gallery_second/>
    </div>
    <x-footer/>
    <script type="" src="./js/footer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
