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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('service_name');
            $table->string('service_description');
            $table->longText('service_icon');
            $table->string('service_url')->nullable();
            $table->boolean('service_url_mode')->nullable();
            $table->integer('service_order')->default(99);
            $table->boolean('actived')->default(true);
            $table->uuid('page_slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
