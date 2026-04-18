<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TratamientoPacienteController;
use App\Http\Controllers\SesionTratamientoController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified', 'sucursal.activa']);

/*
|--------------------------------------------------------------------------
| DASHBOARD (🔴 AQUÍ SE AGREGA sucursal.activa)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'sucursal.activa'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| SUCURSALES (SOLO ADMIN)
|--------------------------------------------------------------------------
*/
Route::get('/sucursales', [SucursalController::class, 'index'])
    ->name('sucursales.index')
    ->middleware(['auth', 'role:admin']);

Route::post('/sucursales-create', [SucursalController::class, 'create']);
Route::get('/sucursales-get', [SucursalController::class, 'GetSucursales']);
Route::get('/sucursales-get/{idsucursal}', [SucursalController::class, 'GetIdSucursales']);
Route::put('/sucursales-update/{id}', [SucursalController::class, 'update']);
Route::delete('/sucursales-delete/{id}', [SucursalController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| USUARIOS (ADMIN)
|--------------------------------------------------------------------------
*/
Route::get('/usuarios', [DoctorController::class, 'index'])
    ->name('usuarios.index')
    ->middleware(['auth', 'role:admin']);

Route::get('/materiales', [ProductoController::class, 'index'])
    ->name('admin.materiales')
    ->middleware(['auth', 'role:admin']);

Route::get('/usuarios-get', [DoctorController::class, 'GetUsuarios']);
Route::post('/usuarios-create', [DoctorController::class, 'create']);

/*
|--------------------------------------------------------------------------
| ASIGNACIÓN DE SUCURSALES (ADMIN)
|--------------------------------------------------------------------------
*/
Route::get('/usuarios-sucursales/{id}', [DoctorController::class, 'getSucursalesAsignadas']);
Route::post('/usuarios-asignar-sucursal', [DoctorController::class, 'asignarSucursal']);

/*
|--------------------------------------------------------------------------
| DOCTOR - SELECCIÓN DE SUCURSAL (🚨 SIN MIDDLEWARE)
|--------------------------------------------------------------------------
*/
Route::get('/doctor-sucursales', [DoctorController::class, 'sucursalesDoctor']);
Route::post('/seleccionar-sucursal', [DoctorController::class, 'seleccionarSucursal']);

Route::get('/seleccionar-sucursal', function () {
    return view('seleccionar-sucursal');
});

Route::post('/cambiar-sucursal', function (Illuminate\Http\Request $request) {
    session(['sucursal_id' => $request->sucursal_id]);
    return response()->json(['ok' => true]);
});

    
Route::get('/listas-pacientes', [DoctorController::class, 'lista'])->name('doctor.lista');
Route::get('/buscar-pacientes', [PacienteController::class, 'buscar']);
Route::get('/paciente/{id}', [PacienteController::class, 'show']);
Route::post('/pacientes', [PacienteController::class, 'CrearPaciente']);
Route::get('/get-paciente', [PacienteController::class, 'GetPaciente']);
Route::post('/pacientes/{paciente}/antecedentes', [PacienteController::class, 'CrearAntecedente']);
Route::get('/pacientes/{paciente}/antecedentes-show', [PacienteController::class, 'MostrarAntecendes'])->name('antecedentes.show');
Route::get('/pacientes/{paciente}/tratamientos', [PacienteController::class, 'PacientesTratamientos'])->name('pacientes.tratamientos');
Route::get('/categoria-get', [TratamientoPacienteController::class, 'GetCategorias']);
Route::post('/pacientes/{paciente}/crear-tratamientos', [TratamientoPacienteController::class, 'CrearTratamiento']);
Route::get('/tratamiento/{tratamiento}/sesiones', [TratamientoPacienteController::class, 'GetTratamientoSesion']);
Route::post('/tratamiento/{tratamiento}/sesiones', [SesionTratamientoController::class, 'CrearSesion']);

/*
|--------------------------------------------------------------------------
| firma digital
|--------------------------------------------------------------------------
*/
Route::post('/generar-token-firma', [FirmaController::class, 'generarToken']);
Route::get('/firmar/{token}', [FirmaController::class, 'verFormulario']);
Route::post('/guardar-firma', [FirmaController::class, 'guardarFirma']);
Route::post('/pagos/adelanto', [PagoController::class, 'guardarAdelanto']);
Route::get('/productos-get', [ProductoController::class, 'GetProductos']);
Route::post('/sesiones/{sesion}/productos', [ProductoController::class, 'CrearProductoSesion']);
Route::get('/sesion/{id}/firma-status', [FirmaController::class, 'firmaStatus']);


Route::get('/materiales-get', [ProductoController::class, 'GetMateriales']);
Route::put('/materiales/{id}', [ProductoController::class, 'update']);
Route::post('/materiales-store', [ProductoController::class, 'CrearMaterial']);
Route::post('/materiales/asignar-sucursales', [ProductoController::class, 'asignarSucursales']);