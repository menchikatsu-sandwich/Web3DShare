<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'models_user_created_idx');
            $table->index(['category_id', 'created_at'], 'models_category_created_idx');
            $table->index(['deleted_at', 'created_at'], 'models_deleted_created_idx');
            $table->index('view_count', 'models_view_count_idx');
            $table->index('download_count', 'models_download_count_idx');
            $table->index('stars_count', 'models_stars_count_idx');
        });

        Schema::table('model_tags', function (Blueprint $table) {
            $table->index(['tag_id', 'model_id'], 'model_tags_tag_model_idx');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['model_id', 'created_at'], 'comments_model_created_idx');
            $table->index(['parent_id', 'created_at'], 'comments_parent_created_idx');
            $table->index(['user_id', 'created_at'], 'comments_user_created_idx');
        });

        Schema::table('stars', function (Blueprint $table) {
            $table->index(['model_id', 'created_at'], 'stars_model_created_idx');
        });

        Schema::table('downloads', function (Blueprint $table) {
            $table->index(['model_id', 'downloaded_at'], 'downloads_model_downloaded_idx');
        });

        Schema::table('model_views', function (Blueprint $table) {
            $table->index(['model_id', 'viewed_at'], 'model_views_model_viewed_idx');
            $table->index(['user_id', 'viewed_at'], 'model_views_user_viewed_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index(['report_status', 'created_at'], 'reports_status_created_idx');
            $table->index(['model_id', 'report_status'], 'reports_model_status_idx');
            $table->index(['reported_by', 'report_status'], 'reports_reporter_status_idx');
        });

        Schema::table('verification_requests', function (Blueprint $table) {
            $table->index(['request_status', 'created_at'], 'verification_status_created_idx');
            $table->index(['user_id', 'request_status'], 'verification_user_status_idx');
            $table->index(['reviewed_by', 'updated_at'], 'verification_reviewer_updated_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'created_at'], 'users_role_created_idx');
            $table->index(['upload_tier', 'created_at'], 'users_tier_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_created_idx');
            $table->dropIndex('users_tier_created_idx');
        });

        Schema::table('verification_requests', function (Blueprint $table) {
            $table->dropIndex('verification_status_created_idx');
            $table->dropIndex('verification_user_status_idx');
            $table->dropIndex('verification_reviewer_updated_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_status_created_idx');
            $table->dropIndex('reports_model_status_idx');
            $table->dropIndex('reports_reporter_status_idx');
        });

        Schema::table('model_views', function (Blueprint $table) {
            $table->dropIndex('model_views_model_viewed_idx');
            $table->dropIndex('model_views_user_viewed_idx');
        });

        Schema::table('downloads', function (Blueprint $table) {
            $table->dropIndex('downloads_model_downloaded_idx');
        });

        Schema::table('stars', function (Blueprint $table) {
            $table->dropIndex('stars_model_created_idx');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_model_created_idx');
            $table->dropIndex('comments_parent_created_idx');
            $table->dropIndex('comments_user_created_idx');
        });

        Schema::table('model_tags', function (Blueprint $table) {
            $table->dropIndex('model_tags_tag_model_idx');
        });

        Schema::table('models', function (Blueprint $table) {
            $table->dropIndex('models_user_created_idx');
            $table->dropIndex('models_category_created_idx');
            $table->dropIndex('models_deleted_created_idx');
            $table->dropIndex('models_view_count_idx');
            $table->dropIndex('models_download_count_idx');
            $table->dropIndex('models_stars_count_idx');
        });
    }
};
