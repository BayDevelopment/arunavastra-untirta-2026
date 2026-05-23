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
        Schema::create('boat_locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('speed', 8, 2)->nullable();
            $table->decimal('altitude', 8, 2)->nullable();
            $table->string('status')->default('online');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boat_locations');
    }
};
