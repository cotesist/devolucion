<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'registro_id',
        'categoria',
        'serie',
        'box_id',
        'mac',
        'mta_mac',
        'estado',
        'adaptador',
        'control',
        'remoto',
        'control_remoto',
        'cable_hdmi',
        'cable_audio_video',
        'cable_rj45',
        'cable_rj11',
        'filtro_tel',
        'observaciones',
        'creado_por',
        'actualizado_por',
    ];

    protected $casts = [
        'adaptador' => 'boolean',
        'control' => 'boolean',
        'remoto' => 'boolean',
        'control_remoto' => 'boolean',
        'cable_hdmi' => 'boolean',
        'cable_audio_video' => 'boolean',
        'cable_rj45' => 'boolean',
        'cable_rj11' => 'boolean',
        'filtro_tel' => 'boolean',
    ];

    // Campos válidos por categoría (para validar en el controlador)
    public const CAMPOS_POR_CATEGORIA = [
        'settop_box' => ['serie', 'box_id', 'estado', 'adaptador', 'control', 'remoto', 'cable_hdmi', 'cable_audio_video', 'observaciones'],
        'decodificador' => ['serie', 'estado', 'control_remoto', 'observaciones'],
        'adsl' => ['serie', 'mac', 'estado', 'adaptador', 'cable_rj45', 'cable_rj11', 'filtro_tel', 'observaciones'],
        'cablemodem' => ['serie', 'mta_mac', 'estado', 'adaptador', 'cable_rj45', 'observaciones'],
        'gepon' => ['serie', 'mac', 'estado', 'adaptador', 'observaciones'],
        'gpon' => ['serie', 'mac', 'estado', 'adaptador', 'cable_rj45', 'cable_rj11', 'observaciones'],
    ];

    public function registro()
    {
        return $this->belongsTo(Registro::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}