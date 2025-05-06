<style>
  .card_slider_container{
    width: 90vw;
    gap: 20px;
    padding: 90px 10px 30px 10px;
    margin: auto;
    height: auto;
    overflow: hidden;
    justify-content: center;
    display: flex;
    position: relative;
  }
  .card_slider_container .card{
    width: 300px;
  }
  .card_slider_container h2{
    position: absolute;
    top: 20px;
    left: 140px;
  }
  .card_slider_container img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-position: center;
    background-size: cover;
  }
  .card_slider_container ul{
    display: flex;
    text-decoration: none;
    list-style-type: none;
    padding: 0;
    gap: 10px;
  }
  .card_slider_container ul li{
    font-size:25px;
  }
  .card_slider_container ul li ion-icon[name="logo-facebook"] {
    color: 	#4267B2;
  }

  .card_slider_container ul li ion-icon[name="logo-whatsapp"] {
    color: #25D366;
}

</style>



<div class="card_slider_container mt-3">
  <h2>Bordul de conducere</h2>
  <div class="card">
    <img class="card-img-top" src="/images/gelu.jpg" alt="Card image">
    <div class="card-body">
      <h4 class="card-title">Gelu Golea</h4>
      <p class="card-text">Here some text about me</p>
      <ul>
        <li><ion-icon name="logo-facebook"></ion-icon></li>
        <li><ion-icon name="logo-whatsapp"></ion-icon></li>
      </ul>
    </div>
  </div>
  <div class="card">
    <img class="card-img-top" src="/images/john.jpg" alt="Card image">
    <div class="card-body">
      <h4 class="card-title">John Dolinsky</h4>
      <p class="card-text">Here some text about me</p>
      <ul>
        <li><ion-icon name="logo-facebook"></ion-icon></li>
        <li><ion-icon name="logo-whatsapp"></ion-icon></li>
      </ul>
    </div>
  </div>
  <div class="card">
    <img class="card-img-top" src="/images/dumitru.jpg" alt="Card image">
    <div class="card-body">
      <h4 class="card-title">Dumitru Stroia</h4>
      <p class="card-text">Here some text about me</p>
      <ul>
        <li><ion-icon name="logo-facebook"></ion-icon></li>
        <li><ion-icon name="logo-whatsapp"></ion-icon></li>
      </ul>
    </div>
  </div>
</div>