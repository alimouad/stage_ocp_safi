<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emission_point_id')->constrained()->cascadeOnDelete();
            $table->foreignId('substance_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 12, 4);
            $table->dateTime('measured_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};