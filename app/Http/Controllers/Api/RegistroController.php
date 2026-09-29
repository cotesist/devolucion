<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        $query = Registro::with(['equipos', 'usuario:id,name', 'editor:id,name'])
            ->orderByDesc('id');

        if ($request->filled('telefono')) {
            $query->where('telefono', $request->telefono);
        }

        return response()->json($query->paginate(20));
    }

    public function show(Registro $registro)
    {
        return response()->json(
            $registro->load(['equipos', 'usuario:id,name', 'editor:id,name'])
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telefono' => ['required', 'regex:/^6[1-4][0-9]{5}$/'],
            'nombre_cliente' => 'required|string|max:255',
            'internet' => 'boolean',
            'television' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $registro = DB::transaction(function () use ($request) {
            return Registro::create([
                'numero_orden' => Registro::generarNumeroOrden(),
                'telefono' => $request->telefono,
                'nombre_cliente' => $request->nombre_cliente,
                'internet' => (bool) $request->boolean('internet'),
                'television' => (bool) $request->boolean('television'),
                'usuario_id' => $request->user('api')->id,
            ]);
        });

        return response()->json($registro, 201);
    }

    public function update(Request $request, Registro $registro)
    {
        $validator = Validator::make($request->all(), [
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $registro->observaciones = $request->observaciones;
        $registro->actualizado_por = $request->user('api')->id;
        $registro->save();

        return response()->json($registro->load(['equipos', 'usuario:id,name', 'editor:id,name']));
    }
}