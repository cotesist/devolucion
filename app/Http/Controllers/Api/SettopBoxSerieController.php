<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\SettopBoxSerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettopBoxSerieController extends Controller
{
    /**
     * Obtener todas las series de un equipo específico.
     */
    public function index(Equipo $equipo)
    {
        return response()->json($equipo->series);
    }

    /**
     * Guardar una nueva serie individual para un equipo.
     */
public function store(Request $request, Equipo $equipo)
{
    $validator = Validator::make($request->all(), [
        'serie' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $serie = $equipo->series()->create([
        'serie' => $request->input('serie'), // Asignación explícita
    ]);

    return response()->json($serie, 201);
}

    /**
     * Actualizar un número de serie específico.
     */
    public function update(Request $request, SettopBoxSerie $settopBoxSerie)
    {
        $validator = Validator::make($request->all(), [
            'serie' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settopBoxSerie->update([
            'serie' => $request->input('serie'),
        ]);

        return response()->json($settopBoxSerie);
    }

    /**
     * Eliminar una serie específica.
     */
    public function destroy(SettopBoxSerie $settopBoxSerie)
    {
        $settopBoxSerie->delete();

        return response()->json(['message' => 'Serie eliminada correctamente']);
    }
}