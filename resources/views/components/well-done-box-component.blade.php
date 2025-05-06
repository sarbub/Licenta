@props([
    'heading' =>'heading goes here',
    'message' => 'message goes here',
    'aditional'=>''
    ])

<style>
    .wellDone{
        width: 100%;
        padding: 10px;
        display: flex;
        justify-content: center;
        padding: 10px;
    }
    .wellDone .alert{
        width: 70%;
    }
</style>
<div class="wellDone">
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">{{ $heading }}</h4>
  <p>{{ $message }}</p>
  <hr>
  <p class="mb-0">{{ $aditional }}</p>
</div>
</div>