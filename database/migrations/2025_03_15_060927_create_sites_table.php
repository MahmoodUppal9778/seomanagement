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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->nullable();
            $table->string('currency')->nullable();
            $table->integer('price_guest_post')->nullable();
            $table->integer('price_link_insertion')->nullable();
            $table->string('niche')->nullable();
            $table->integer('sale_price_guest_post')->nullable();
            $table->integer('sale_price_link_insertion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
