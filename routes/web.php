<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\FormacionController;
use Illuminate\Support\Facades\Broadcast;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/log-in', [AuthController::class, 'login'])->name('login.custom'); 
Route::get('/signout', [AuthController::class, 'signOut'])->name('signout');

Route::middleware(['auth'])->group(function () {
    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/crear-ticket', [TicketController::class, 'VerCrearTicket'])->name('ver.crearticket');

    Route::post('/guardar-ticket', [TicketController::class, 'GuardarTicket'])->name('guardar.ticket');
    Route::get('/ticket/{ticket}', [TicketController::class, 'mostrarTicket'])->name('ticket.mostrado');
    Route::get('/Solicitudes-Sin-Asignar', [TicketController::class, 'VerSolicitudesSinAsig'])->name('ver.solicitudes.totales');


    Route::post('/tickets/asignar', [TicketController::class, 'asignarTicket'])->name('asignar');
    Route::get('/tickets/get-users', [TicketController::class, 'getUsersByTicket'])->name('tickets.get-users');

    Route::get('/mis-solicitudes', [TicketController::class, 'verMisSolicitudes'])->name('vista-missolicitudes');
    Route::get('/editar/{id}/ver', [TicketController::class, 'VerEditarTicket'])->name('ver.Editar');
    Route::post('/editar/{id}', [TicketController::class, 'GuardarEditar'])->name('guardar.editar');
    Route::prefix('/ticketsdel')->group(function() {
        Route::delete('/{id}', [TicketController::class, 'EliminarTicket']);
    });

    Route::get('/tickets/{ticket}/archivo/eliminar/{archivo}', [TicketController::class, 'eliminarArchivo'])->name('eliminar.archivo');
    Route::get('/Peticiones', [TicketController::class, 'VerPeticiones'])->name('ver.peticiones');
    Route::get('/tickets/cerrar/{id}', [TicketController::class, 'CerrarTicket'])->name('cerrar.ticket');
    Route::get('/tickets/cerrados', [TicketController::class, 'VerCerrados'])->name('ver.cerrados');
    Route::get('/tickets/totales', [TicketController::class, 'VerTotales'])->name('ver.solicitudes.departamento');
    Route::get('/motivos/pausa', [TicketController::class, 'getMotivosPausa'])->name('motivos.pausa');
    Route::post('/tickets/{ticket}/actualizar/estado', [TicketController::class, 'actualizarEstado'])->name('tickets.actualizar.estado');


    Route::get('/export-tickets', [DashboardController::class, 'exportData'])->name('tickets.export');
    Route::get('/manual-ayuda', [DashboardController::class, 'verManual'])->name('ver-manual');

    Route::get('/listado-campana', [AdministracionController::class, 'ListadoCampanas'])->name('listado.campana');

    Route::post('/guardar/campana/{id}', [AdministracionController::class, 'GuardarEdiarCampana'])->name('guardar.editar.campana');

    Route::post('/SaveCampaing', [AdministracionController::class, 'AddCampana'])->name('guardar.add.campaign');

    Route::prefix('/campanasdel')->group(function() {
        Route::delete('/{id}', [AdministracionController::class, 'EliminarCampana']);
    });

    Route::get('/listado-categorias', [AdministracionController::class, 'ListadoCategorias'])->name('listado.categorias');

    Route::post('/guardar/categoria/{id}', [AdministracionController::class, 'GuardarEdiarCategorias'])->name('guardar.editar.categorias');

    Route::post('/SaveCategoria', [AdministracionController::class, 'AddCategoria'])->name('guardar.add.categorias');

    Route::prefix('/categoriasdel')->group(function() {
        Route::delete('/{id}', [AdministracionController::class, 'EliminarCategoria']);
    });

    Route::get('/listado-sedes', [AdministracionController::class, 'ListadoSedes'])->name('listado.sedes');

    Route::post('/guardar/sede/{id}', [AdministracionController::class, 'GuardarEdiarSedes'])->name('guardar.editar.sedes');

    Route::post('/SaveSede', [AdministracionController::class, 'AddSede'])->name('guardar.add.sedes');

    Route::prefix('/sedesdel')->group(function() {
        Route::delete('/{id}', [AdministracionController::class, 'EliminarSede']);
    });


    Route::get('/listado-tipos', [AdministracionController::class, 'ListadoTipos'])->name('listado.tipos');

    Route::post('/guardar/Tipo/{id}', [AdministracionController::class, 'GuardarEdiarTipos'])->name('guardar.editar.tipos');

    Route::post('/SaveTipo', [AdministracionController::class, 'AddTipo'])->name('guardar.add.tipos');

    Route::prefix('/tiposdel')->group(function() {
        Route::delete('/{id}', [AdministracionController::class, 'EliminarTipo']);
    });



    Route::get('/listado-motivos', [AdministracionController::class, 'ListadoMotivos'])->name('listado.motivos');

    Route::post('/guardar/motivos/{id}', [AdministracionController::class, 'GuardarEdiarMotivos'])->name('guardar.editar.motivos');

    Route::post('/SaveMotivo', [AdministracionController::class, 'AddMotivo'])->name('guardar.add.motivos');

    Route::prefix('/motivosdel')->group(function() {
        Route::delete('/{id}', [AdministracionController::class, 'EliminarMotivo']);
    });



    Route::get('/forms', [FormacionController::class, 'VerForms'])->name('ver.formacion');
    Route::get('/listado-forms', [FormacionController::class, 'ListadoForms'])->name('listado.forms');
    Route::post('/guardar-forms', [FormacionController::class, 'SaveForms'])->name('save.forms');
    Route::get('/cerrar/{id}', [FormacionController::class, 'cerrarFormacion'])->name('cerrar.formacion');
    Route::get('/editar/{id}/forms', [FormacionController::class, 'verEditar'])->name('ver.Editar.forms');
    Route::get('/ver/{id}', [FormacionController::class, 'Ver'])->name('ver.form');
    Route::post('/editar-forms/{id}', [FormacionController::class, 'guardarEditar'])->name('guardar.editar.forms');
    Route::prefix('/formsdel')->group(function() {
        Route::delete('/{id}', [FormacionController::class, 'Eliminar']);
    });

    Route::get('/export-forms', [FormacionController::class, 'exportForms'])->name('forms.export');

});