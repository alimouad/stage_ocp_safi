<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emission_points', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->geometry('location', 'point', 4326)->nullable();
            $table->string('factory_zone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emission_points');
    }
};