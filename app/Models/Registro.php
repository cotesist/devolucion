<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_orden',
        'telefono',
        'nombre_cliente',
        'internet',
        'television',
        'observaciones',
        'usuario_id',
        'actualizado_por',
    ];

    protected $casts = [
        'internet' => 'boolean',
        'television' => 'boolean',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    /**
     * Genera un número de orden único correlativo, ej: ORD-20260729-0001
     */
    public static function generarNumeroOrden(): string
    {
        $fecha = now()->format('Ymd');
        $prefijo = "ORD-{$fecha}-";
        $ultimo = static::where('numero_orden', 'like', $prefijo . '%')
            ->orderByDesc('id')
            ->value('numero_orden');

        $correlativo = 1;
        if ($ultimo) {
            $correlativo = (int) substr($ultimo, -4) + 1;
        }

        return $prefijo . str_pad($correlativo, 4, '0', STR_PAD_LEFT);
    }
}