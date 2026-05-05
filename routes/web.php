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
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('home')->middleware(['auth', 'verified', 'sucursal.activa']);

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

Route::get('/categorias', [CategoriaController::class, 'index'])
    ->name('admin.categorias')
    ->middleware(['auth', 'role:admin']);

Route::get('/calendar', [CalendarController::class, 'index'])
    ->name('admin.calendar');

Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('admin.reportes');

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
Route::get('/cajas', [CajaController::class, 'index'])->name('admin.cajas');
Route::get('/cajas-admin', [CajaController::class, 'CajaAdmin'])->name('admin.AdminCaja');
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
Route::get('/tratamientos-get', [TratamientoPacienteController::class, 'TratamientosGet']);
Route::get('/sesiones-get', [TratamientoPacienteController::class, 'SesionesGet']);
Route::get('/tratamientos-select/{tratamientoId}', [TratamientoPacienteController::class, 'TratamientosSelect']);
Route::post('/tratamientos/{id}/concluir', [TratamientoPacienteController::class, 'concluir'])->name('tratamientos.concluir');
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
Route::get('/filtrar-datos-caja-Efectivo', [CajaController::class, 'FiltrarCajaEfectivo']);
Route::post('/movimientos-caja/egreso', [CajaController::class, 'registrarEgreso']);
Route::get('/filtrar-datos-caja-Deposito', [CajaController::class, 'FiltrarCajaDeposito']);
Route::get('/filtrar-datos-caja-Tarjeta', [CajaController::class, 'FiltrarCajaTarjeta']);
Route::get('/filtrar-datos-caja-Efectivo-admin', [CajaController::class, 'FiltrarCajaEfectivoAdmin']);
Route::get('/filtrar-datos-caja-Deposito-admin', [CajaController::class, 'FiltrarCajaDepositoAdmin']);
Route::get('/filtrar-datos-caja-Tarjeta-admin', [CajaController::class, 'FiltrarCajaTarjetaAdmin']);


/**REportes */
Route::post('/reporte-grafico-ingreso', [ReporteController::class, 'graficoIngresos']);
Route::get('/paciente-full/{id}/pdf', [PacienteController::class, 'exportarPacienteFullPDF']);
Route::get('/paciente-tratamiento-especifico/{id}/pdf', [PacienteController::class, 'exportarTratamientoEspecificoPDF']);
Route::post('/reporte-pdf-sucursales-ingresos', [ReporteController::class, 'generarSucursalesPDF']);
Route::post('/reporte-pdf-tratamientos-sesiones', [ReporteController::class, 'generarTratamientosSesionesPDF'])->name('reporte.tratamientos.pdf');
Route::post('/Categoria-Tratamiento-store', [CategoriaController::class, 'CrearCategoria']);
Route::get('/categoria-Tratamiento-get', [CategoriaController::class, 'GetCategorias']);
Route::put('/Categoria-Tratamiento/{id}', [CategoriaController::class, 'update']);

