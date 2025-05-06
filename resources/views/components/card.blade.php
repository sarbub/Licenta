@props([
    'id' => 'myCard',
    'title' =>'myTitle',
    'text'=>'text goes here',
    'button_text' =>'learn more',
    'color' =>'#e9ecef',
    'textColor' => '#1c1c1c',
    'display' => 'inline-block',
    'btn_background' => '#007bff',
    'btn_text_color' =>"#fff",
    'link'=>'#'
    ])

<style>
    
    .card_container{
        display: flex;
        align-items:center;
        justify-content: center;
        align-items: center;
        padding: 10px;
        width: 100%!important;
}
.card_container .jumbotron{
          padding:20px;
            border-radius: 10px;
            width: 70%;
        }
</style>
<div class = "card_container">
        <div class="jumbotron ourMissionJumbotron" style = "background-color: {{ $color }}">
            <h1 class="display-6" style="color:{{ $textColor }}">{{ $title }}</h1>
            <p class="lead" style = "color:{{ $textColor }}">{{ $text }}</p>
            <a class="btn btn-primary btn-lg" href="{{ $link }}" role="button" style = "display:{{ $display }}; background-color:{{ $btn_background }}; border:none; color:{{ $btn_text_color }}">{{$button_text}}</a>
        </div>
</div>
