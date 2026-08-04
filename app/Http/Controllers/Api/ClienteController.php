<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function buscar(string $telefono)
    {
        $validator = Validator::make(['telefono' => $telefono], [
            // Exactamente 7 dígitos, rango 6100000 - 6499999
            'telefono' => ['required', 'regex:/^6[1-4][0-9]{5}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'El teléfono debe tener 7 dígitos y estar en el rango 6100000 - 6499999.'
            ], 422);
        }

        try {
            $response = Http::timeout(10)
                ->get("http://localhost/api/v1/cliente/{$telefono}");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo conectar con el servicio de clientes.'
            ], 502);
        }

        if ($response->status() === 404) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Error al consultar el servicio de clientes.'
            ], 502);
        }

        return response()->json($response->json());
    }
}