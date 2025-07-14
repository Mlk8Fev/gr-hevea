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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom')->after('name');
            $table->string('prenom')->after('nom');
            $table->string('role')->default('user')->after('prenom');
            $table->string('secteur')->nullable()->after('role');
            $table->string('fonction')->nullable()->after('secteur');
            $table->boolean('siege')->default(false)->after('fonction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom', 'role', 'secteur', 'fonction', 'siege']);
        });
    }
};
