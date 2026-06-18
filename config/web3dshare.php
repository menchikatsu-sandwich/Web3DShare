<?php

return [
    'verification' => [
        'min_models' => (int) env('VERIFY_MIN_MODELS', 1),
        'min_downloads_per_model' => (int) env('VERIFY_MIN_DOWNLOADS_PER_MODEL', 1),
        'min_account_age_days' => (int) env('VERIFY_MIN_ACCOUNT_AGE_DAYS', 1),
        'rejection_cooldown_hours' => (int) env('VERIFY_REJECTION_COOLDOWN_HOURS', 1),
    ],
];
