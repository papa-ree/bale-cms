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
        Schema::create('navigations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('navigation_name');
            $table->string('navigation_slug')->unique();
            $table->string('navigation_url')->nullable();
            $table->boolean('navigation_url_mode')->nullable();
            $table->integer('navigation_order');
            $table->boolean('actived')->default(true);
            $table->uuid('parent_id')->nullable();
            $table->uuid('page_slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
