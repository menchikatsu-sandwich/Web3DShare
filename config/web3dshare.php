<?php

return [
    'cache' => [
        'store' => env('WEB3D_CACHE_STORE', 'file'),
        'categories_ttl' => (int) env('WEB3D_CACHE_CATEGORIES_SECONDS', 600),
        'tags_ttl' => (int) env('WEB3D_CACHE_TAGS_SECONDS', 600),
    ],

    'limits' => [
        'basic_monthly_uploads' => (int) env('WEB3D_BASIC_MONTHLY_UPLOADS', 5),
        'model_upload_kb' => (int) env('WEB3D_MODEL_UPLOAD_KB', 50000),
        'thumbnail_upload_kb' => (int) env('WEB3D_THUMBNAIL_UPLOAD_KB', 5000),
        'profile_image_upload_kb' => (int) env('WEB3D_PROFILE_IMAGE_UPLOAD_KB', 5000),
        'max_tags_per_model' => (int) env('WEB3D_MAX_TAGS_PER_MODEL', 10),
    ],

    'engagement' => [
        'view_cooldown_minutes' => (int) env('WEB3D_VIEW_COOLDOWN_MINUTES', 60),
        'download_cooldown_minutes' => (int) env('WEB3D_DOWNLOAD_COOLDOWN_MINUTES', 360),
    ],

    'verification' => [
        'min_models' => (int) env('VERIFY_MIN_MODELS', 1),
        // Keep the old variable as a fallback so existing local environments continue to work.
        'min_total_downloads' => (int) env('VERIFY_MIN_TOTAL_DOWNLOADS', env('VERIFY_MIN_DOWNLOADS_PER_MODEL', 1)),
        'min_account_age_days' => (int) env('VERIFY_MIN_ACCOUNT_AGE_DAYS', 1),
        'rejection_cooldown_hours' => (int) env('VERIFY_REJECTION_COOLDOWN_HOURS', 1),
    ],
];
