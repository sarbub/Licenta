
@props([
    'strong' =>"Strong text here",
    'warning' => 'warning text goes here'
    ])
<style>
    .mainDismissAlert {
        width: 100vw;
        padding: 10px;
        display: flex;
        justify-content: center;
    }

    .mainDismissAlert .alert {
        width: 75%;
    }
</style>
<div class="mainDismissAlert">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{ $strong }}</strong> {{ $warning }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
