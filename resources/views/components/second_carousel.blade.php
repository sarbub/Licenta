<style>
    .second_carousel_main {
        width: 80%;
        position: relative;
        border: 2px solid red;
        height: auto; /* Allow it to expand */
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        margin: auto;
    }

    .second_carousel {
        display: flex;
        flex-wrap: nowrap;
        border: 2px solid blue;
        width: 70%;
        overflow: hidden;
        position: relative;
    }

    .card {
        flex: 0 0 30%; /* Show around 3 cards */
        background-color: lightgray;
        text-align: center;
        padding: 20px;
        margin: 5px;
        border-radius: 5px;
    }

    .second_carousel_main button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: black;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    #second_carousel_prev_button {
        left: 10px;
    }

    #second_carousel_next_button {
        right: 10px;
    }

</style>

<div class="second_carousel_main">
    <div class="second_carousel col-12">
        <div class="inside_second_carousel">
            <div class="card">This is my card 1</div>
            <div class="card">This is my card 2</div>
            <div class="card">This is my card 3</div>
            <div class="card">This is my card 4</div>
        </div>
    </div>
    <button id="second_carousel_prev_button"><ion-icon name="chevron-back-outline"></ion-icon></button>
    <button id="second_carousel_next_button"><ion-icon name="chevron-forward-outline"></ion-icon></button>
</div>

<script>
    const prev_button = document.getElementById("second_carousel_prev_button");
    const next_button = document.getElementById("second_carousel_next_button");
    const carousel = document.querySelector('.inside_second_carousel');

    let offset = 0;
    const cardWidth = document.querySelector('.card').offsetWidth + 10; // 10px for margin

    prev_button.addEventListener("click", () => {
        if (offset > 0) {
            offset -= cardWidth;
            carousel.style.transform = `translateX(-${offset}px)`;
        }
    });

    next_button.addEventListener("click", () => {
        if (offset < (carousel.scrollWidth - carousel.offsetWidth)) {
            offset += cardWidth;
            carousel.style.transform = `translateX(-${offset}px)`;
        }
    });
</script>
