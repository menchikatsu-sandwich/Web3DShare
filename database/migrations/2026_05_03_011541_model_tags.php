<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_tags', function (Blueprint $table) {

            $table->foreignId('model_id')
                ->constrained('models')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->primary(['model_id','tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_tags');
    }
};