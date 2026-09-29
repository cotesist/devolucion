<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    /**
     * Carga automática de relaciones en todas las consultas de Eloquent.
     * Esto asegura que 'series' siempre esté presente en el JSON de la API.
     */
    protected $with = ['series'];

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

    // Campos válidos por categoría
    public const CAMPOS_POR_CATEGORIA = [
        'settop_box' => ['serie', 'series', 'box_id', 'estado', 'adaptador', 'control', 'remoto', 'cable_hdmi', 'cable_audio_video', 'observaciones'],
        'decodificador' => ['serie', 'series', 'estado', 'control_remoto', 'observaciones'],
        'adsl' => ['serie', 'series', 'mac', 'estado', 'adaptador', 'cable_rj45', 'cable_rj11', 'filtro_tel', 'observaciones'],
        'cablemodem' => ['serie', 'series', 'mta_mac', 'estado', 'adaptador', 'cable_rj45', 'observaciones'],
        'gepon' => ['serie', 'series', 'mac', 'estado', 'adaptador', 'observaciones'],
        'gpon' => ['serie', 'series', 'mac', 'estado', 'adaptador', 'cable_rj45', 'cable_rj11', 'observaciones'],
    ];

    /**
     * Relación uno a muchos con la tabla de series secundarias.
     */
    public function series()
    {
        return $this->hasMany(SettopBoxSerie::class, 'equipo_id');
    }

    public function registro()
    {
        return $this->belongsTo(Registro::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizador()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }
}