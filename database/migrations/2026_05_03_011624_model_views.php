<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_views', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                ->constrained('models')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_views');
    }
};