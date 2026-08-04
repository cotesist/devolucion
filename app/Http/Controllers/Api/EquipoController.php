<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller
{
    protected function reglasBase(): array
    {
        return [
            'categoria' => 'required|in:settop_box,decodificador,adsl,cablemodem,gepon,gpon',
            'serie' => 'nullable|string|max:255',
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
        $data['registro_id'] = $registro->id;
        $data['creado_por'] = $request->user('api')->id;

        $equipo = Equipo::create($data);

        return response()->json($equipo, 201);
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validator = Validator::make($request->all(), $this->reglasBase());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['actualizado_por'] = $request->user('api')->id;

        $equipo->update($data);

        return response()->json($equipo);
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return response()->json(['message' => 'Equipo eliminado correctamente']);
    }
}