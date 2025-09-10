<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('farmer_lists', function (Blueprint $table) {
            $table->string('code_producteur')->nullable()->after('contact_producteur');
        });
    }

    public function down()
    {
        Schema::table('farmer_lists', function (Blueprint $table) {
            $table->dropColumn('code_producteur');
        });
    }
}; 