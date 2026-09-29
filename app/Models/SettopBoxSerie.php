<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettopBoxSerie extends Model
{
    use HasFactory;

    // Nombre exacto de tu tabla según la estructura SQL
    protected $table = 'settop_box_series';

    protected $fillable = [
        'equipo_id',
        'serie',
    ];

    /**
     * Relación inversa con el modelo Equipo.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }
}