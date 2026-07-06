@extends ('layouts.app')

@push ('head')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
@endpush

@section ('content')
    <!-- Hapus batasan tinggi di mobile, pastikan lg:h-[] ada untuk mengunci versi PC -->
    <div
        id="model-root"
        class="is-full-page -m-3 flex min-h-screen w-auto flex-col bg-white sm:-m-6 lg:-m-8 lg:h-[calc(100dvh-65px)] dark:bg-darkPanel"
    >
        @include ('model.partial', [
            'model' => $model,
            'recommendations' => $recommendations,
            'categories' => $categories,
            'isManageContext' => $isManageContext,
            'authorModelCount' => $authorModelCount,
            'hasStarred' => $hasStarred,
        ])
    </div>
    <style>
        .is-full-page #close-modal-btn {
            display: none !important;
        }

        /* Munculkan tombol Back to Explore */
        .is-full-page #back-to-explore-btn {
            display: flex !important;
        }
    </style>
@endsection