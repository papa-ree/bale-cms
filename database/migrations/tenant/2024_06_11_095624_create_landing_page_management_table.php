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
        Schema::create('landing_page_management', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('landing_page_value')->nullable();
            $table->string('landing_page_url')->nullable();
            $table->boolean('landing_page_media_url_mode')->nullable();
            $table->string('landing_page_media_url')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->boolean('actived')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_management');
    }
};
