<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('farmer_lists', function (Blueprint $table) {
            $table->integer('nombre_sacs')->nullable()->after('quantite_livree');
        });
    }

    public function down()
    {
        Schema::table('farmer_lists', function (Blueprint $table) {
            $table->dropColumn('nombre_sacs');
        });
    }
}; 