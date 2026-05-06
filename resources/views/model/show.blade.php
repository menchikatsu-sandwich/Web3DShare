@extends('layouts.app')

@section('content')

<div id="model-root" class="is-full-page flex flex-col w-auto h-[calc(100vh-73px)] -m-6 lg:-m-8 bg-white dark:bg-darkPanel">
    
    @include('model.partial', ['model' => $model, 'recommendations' => $recommendations])

</div>

<style>
    .is-full-page #close-modal-btn {
        display: none !important;
    }
</style>

@endsection