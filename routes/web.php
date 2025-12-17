<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\UserController;    
use App\Http\Controllers\ProductoController; 
use App\Http\Controllers\MesaAdminController;
use App\Http\Controllers\CorteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return redirect()->route('mesas');
})->middleware(['auth', 'verified'])->name('dashboard');


// =========================================================================
// 2. PANEL ADMINISTRATIVO (LA ZONA VIP)
// =========================================================================
// Ruta para ver el menú de tarjetas (Amarilla, Roja y Azul).
Route::get('/admin/panel', function () {
    return view('dashboard'); 
})->middleware(['auth', 'role:Admin'])->name('admin.panel');


// Rutas de Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Mesas y Órdenes
Route::middleware(['auth'])->group(function () {
    Route::get('/mesas', [MesaController::class, 'index'])->name('mesas');

    // Ruta que gestiona buscar-o-crear la orden para una mesa
    Route::get('/orden/mesa/{mesa}', [OrdenController::class, 'gestionarOrdenPorMesa'])
        ->name('orden.gestionar');

    // Ruta que muestra una orden en particular
    Route::get('/orden/{orden}', [OrdenController::class, 'mostrarOrden'])
        ->name('orden.mostrar');

    // Ruta para agregar items en lote a la orden
    Route::post('/orden/{orden}/agregar', [OrdenController::class, 'agregarItems'])
        ->name('orden.agregar');

    // Ruta para cerrar una orden (marcar como pagada y liberar la mesa)
    Route::post('/orden/{orden}/cerrar', [OrdenController::class, 'cerrarOrden'])
        ->name('orden.cerrar');

    // Ruta para procesar pago vía AJAX
    Route::post('/orden/pagar', [OrdenController::class, 'pagarCuenta'])->name('orden.pagar');

    // Rutas para imprimir ticket y comanda
    Route::get('/imprimir/ticket/{orden}', [OrdenController::class, 'imprimirTicket'])->name('imprimir.ticket');
    Route::get('/imprimir/comanda/{orden}', [OrdenController::class, 'imprimirComanda'])->name('imprimir.comanda');
});

// Rutas de Administración (Usuarios y Productos)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    
    // Usuarios (Tarjeta Roja)
    // Asegúrate de que UserController exista en App/Http/Controllers
    Route::resource('admin/users', UserController::class)->names('admin.users');

    // Redirección para arreglar el botón viejo "Gestión de Usuarios"
    Route::get('/admin/gestion', function() { 
        return redirect()->route('admin.users.index'); 
    })->name('admin.gestion');

    // Productos (Tarjeta Azul)
    // Asegúrate de que ProductoController exista en App/Http/Controllers
    Route::resource('admin/productos', ProductoController::class)->names('admin.productos');

    // Mesas (Administración)
    Route::resource('admin/mesas', MesaAdminController::class)->names('admin.mesas');

    // Cortes y reportes financieros
    Route::get('/admin/cortes', [CorteController::class, 'index'])->name('admin.cortes.index');
    Route::post('/admin/cortes/reporte', [CorteController::class, 'generarReporte'])->name('admin.cortes.reporte');
    Route::post('/admin/cortes/guardar', [CorteController::class, 'guardarCorte'])->name('admin.cortes.guardar');
});


require __DIR__.'/auth.php';
