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
            // Colonnes pour le 2FA par email
            $table->boolean('email_2fa_enabled')->default(false)->after('role');
            $table->timestamp('email_2fa_enabled_at')->nullable()->after('email_2fa_enabled');
            $table->string('email_2fa_code')->nullable()->after('email_2fa_enabled_at');
            $table->timestamp('email_2fa_code_expires_at')->nullable()->after('email_2fa_code');
            $table->integer('email_2fa_attempts')->default(0)->after('email_2fa_code_expires_at');
            $table->timestamp('email_2fa_locked_until')->nullable()->after('email_2fa_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_2fa_enabled',
                'email_2fa_enabled_at',
                'email_2fa_code',
                'email_2fa_code_expires_at',
                'email_2fa_attempts',
                'email_2fa_locked_until'
            ]);
        });
    }
};
