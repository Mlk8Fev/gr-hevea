<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            $table->string('agronica_id')->nullable()->after('code_fphci');
            $table->string('localite')->nullable()->after('agronica_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            $table->dropColumn(['agronica_id', 'localite']);
        });
    }
};
