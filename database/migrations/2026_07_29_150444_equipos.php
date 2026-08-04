<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_id')->constrained('registros')->onDelete('cascade');
            $table->enum('categoria', [
                'settop_box', 'decodificador', 'adsl', 'cablemodem', 'gepon', 'gpon'
            ]);
            $table->string('serie')->nullable();      // serie / serie AE
            $table->string('box_id')->nullable();      // SETTOP BOX
            $table->string('mac')->nullable();         // ADSL / CABLEMODEM / GEPON / GPON
            $table->string('mta_mac')->nullable();      // CABLEMODEM
            $table->enum('estado', ['bien', 'mal'])->default('bien');
            $table->boolean('adaptador')->default(false);
            $table->boolean('control')->default(false);        // SETTOP BOX
            $table->boolean('remoto')->default(false);         // SETTOP BOX
            $table->boolean('control_remoto')->default(false); // DECODIFICADOR
            $table->boolean('cable_hdmi')->default(false);     // SETTOP BOX
            $table->boolean('cable_audio_video')->default(false); // SETTOP BOX
            $table->boolean('cable_rj45')->default(false);     // ADSL / CABLEMODEM / GPON
            $table->boolean('cable_rj11')->default(false);     // ADSL / GPON
            $table->boolean('filtro_tel')->default(false);     // ADSL
            $table->text('observaciones')->nullable();
            $table->foreignId('creado_por')->constrained('users');
            $table->foreignId('actualizado_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};