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
        Schema::create('personal_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('personal_access_token_id')->constrained('personal_access_tokens')->onDelete('cascade');
            $table->uuid('personal_token_plain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_tokens');
    }
};
