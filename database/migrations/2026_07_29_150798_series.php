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
        Schema::create('settop_box_series', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tabla 'equipos' (Foreign Key con ON DELETE CASCADE)
            $table->foreignId('equipo_id')
                  ->constrained('equipos')
                  ->onDelete('cascade');

            $table->string('serie', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settop_box_series');
    }
};
