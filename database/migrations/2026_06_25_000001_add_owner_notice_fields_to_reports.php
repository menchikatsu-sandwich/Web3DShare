<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->text('owner_message')->nullable()->after('description');
            $table->string('owner_action')->nullable()->after('owner_message');
            $table->timestamp('owner_notified_at')->nullable()->after('owner_action');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'owner_message',
                'owner_action',
                'owner_notified_at',
            ]);
        });
    }
};
