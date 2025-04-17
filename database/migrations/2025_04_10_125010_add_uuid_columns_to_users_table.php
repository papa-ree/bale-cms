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
            $table->uuid('uuid')
                ->unique()
                ->after('id');
            $table->uuid('username')
                ->unique()
                ->after('name');
            $table->string('fcm_token')->nullable()->after('password');
            $table->string('two_factor_cookies')->nullable()->after('fcm_token');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'username',
                'fcm_token',
                'two_factor_cookies',
            ]);
        });
    }
};
