<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EquipoController;
use App\Http\Controllers\Api\RegistroController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
// SOLO PARA PRUEBAS — simula el servicio externo de clientes
Route::middleware('auth:api')->get('/cliente/{telefono}', function (string $telefono) {
    $clientesPrueba = [
        '6453562' => ['nombre' => 'ORO MARIO', 'telefono' => '6453562', 'internet' => true, 'television' => true],
        '6100001' => ['nombre' => 'PEREZ ANA LUCIA', 'telefono' => '6100001', 'internet' => true, 'television' => false],
        '6234567' => ['nombre' => 'GONZALES LUIS', 'telefono' => '6234567', 'internet' => false, 'television' => true],
        '6399999' => ['nombre' => 'FLORES ROSA', 'telefono' => '6399999', 'internet' => true, 'television' => true],
        '6478901' => ['nombre' => 'QUISPE JORGE', 'telefono' => '6478901', 'internet' => true, 'television' => false],
    ];

    if (!isset($clientesPrueba[$telefono])) {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }

    return response()->json($clientesPrueba[$telefono]);
});

// 🟢 RUTAS PÚBLICAS (Sin token / auth)
Route::post('/login', [AuthController::class, 'login']);


// 🔒 RUTAS PROTEGIDAS (Requieren Token Bearer)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Solo administradores gestionan usuarios
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('usuarios', UsuarioController::class)->except(['show']);
    });

    // Búsqueda de cliente por teléfono
  //  Route::get('/cliente/{telefono}', [ClienteController::class, 'buscar']);

    // Registros (órdenes de devolución)
    Route::get('/registros', [RegistroController::class, 'index']);
    Route::post('/registros', [RegistroController::class, 'store']);
    Route::get('/registros/{registro}', [RegistroController::class, 'show']);
    Route::put('/registros/{registro}', [RegistroController::class, 'update']);

    // Equipos dentro de un registro
    Route::post('/registros/{registro}/equipos', [EquipoController::class, 'store']);
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update']);
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy']);
});