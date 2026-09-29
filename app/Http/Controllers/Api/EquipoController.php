<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller
{
    protected function reglasBase(): array
    {
        return [
            'categoria' => 'required|in:settop_box,decodificador,adsl,cablemodem,gepon,gpon',
            'serie' => 'nullable|string|max:255',
            'series' => 'nullable|array',
            'series.*' => 'required|string|max:255',
            'box_id' => 'nullable|string|max:255',
            'mac' => 'nullable|string|max:255',
            'mta_mac' => 'nullable|string|max:255',
            'estado' => 'required|in:bien,mal',
            'adaptador' => 'boolean',
            'control' => 'boolean',
            'remoto' => 'boolean',
            'control_remoto' => 'boolean',
            'cable_hdmi' => 'boolean',
            'cable_audio_video' => 'boolean',
            'cable_rj45' => 'boolean',
            'cable_rj11' => 'boolean',
            'filtro_tel' => 'boolean',
            'observaciones' => 'nullable|string',
        ];
    }

    public function store(Request $request, Registro $registro)
    {
        $validator = Validator::make($request->all(), $this->reglasBase());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $series = $data['series'] ?? [];
        unset($data['series']);

        $data['registro_id'] = $registro->id;
        $data['creado_por'] = $request->user('api')->id;

        $equipo = DB::transaction(function () use ($data, $series) {
            $nuevoEquipo = Equipo::create($data);

            if (!empty($series)) {
                // Se mapea a la clave 'serie' que corresponde a la columna de la tabla
                $seriesData = array_map(function ($numSerie) {
                    return ['serie' => $numSerie];
                }, $series);

                $nuevoEquipo->series()->createMany($seriesData);
            }

            return $nuevoEquipo;
        });

        return response()->json($equipo->load('series'), 201);
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validator = Validator::make($request->all(), $this->reglasBase());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $series = $data['series'] ?? null;
        unset($data['series']);

        $data['actualizado_por'] = $request->user('api')->id;

        DB::transaction(function () use ($equipo, $data, $series) {
            $equipo->update($data);

            if (is_array($series)) {
                $equipo->series()->delete();

                if (!empty($series)) {
                    // Corrección de la clave a 'serie'
                    $seriesData = array_map(function ($numSerie) {
                        return ['serie' => $numSerie];
                    }, $series);

                    $equipo->series()->createMany($seriesData);
                }
            }
        });

        return response()->json($equipo->load('series'));
    }

    public function destroy(Equipo $equipo)
    {
        DB::transaction(function () use ($equipo) {
            $equipo->series()->delete();
            $equipo->delete();
        });

        return response()->json(['message' => 'Equipo eliminado correctamente']);
    }
}