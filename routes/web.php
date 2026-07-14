<?php

// Definir rutas

use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\CursoController;
use App\Controllers\Admin\MenuController;
use App\Controllers\Admin\NivelController;
use App\Controllers\Admin\PermisoController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\SubnivelController;
use App\Controllers\Admin\UserController;
use App\Controllers\AuthController;

use Core\Route;

// Ahora sí encontrará perfectamente la carpeta Core en la raíz del proyecto
require_once RAIZ_PROYECTO . '/Core/middlewares.php';

Route::get('/', [AuthController::class, 'showLoginForm']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/logout', [AuthController::class, 'logout']);

Route::get('admin/dashboard', [AdminDashboardController::class, 'index'], [$authMiddleware]);

/** Rutas para Permisos (Quita el / del inicio si tu enrutador usa trim($uri, '/')) */
Route::get('permissions', [PermisoController::class, 'index'], [$authMiddleware]);
Route::get('permissions/create', [PermisoController::class, 'create'], [$authMiddleware]);
Route::post('permissions', [PermisoController::class, 'store'], [$authMiddleware]);
// Ver el listado de la papelera (GET)
Route::get('permissions/wastebasket', [PermisoController::class, 'wastebasket'], [$authMiddleware]);
Route::post('permissions/:id/restore', [PermisoController::class, 'restore'], [$authMiddleware]);
Route::post('permissions/:id/destroy', [PermisoController::class, 'destroy'], [$authMiddleware]);
// Rutas comunes
Route::get('permissions/:id/edit', [PermisoController::class, 'edit'], [$authMiddleware]);
Route::post('permissions/:id/update', [PermisoController::class, 'update'], [$authMiddleware]);
Route::post('permissions/:id/delete', [PermisoController::class, 'delete'], [$authMiddleware]);

/** Rutas para Roles */
Route::get('roles', [RoleController::class, 'index'], [$authMiddleware]);
Route::get('roles/create', [RoleController::class, 'create'], [$authMiddleware]);
Route::post('roles', [RoleController::class, 'store'], [$authMiddleware]);
// Ver el listado de la papelera (GET)
Route::get('roles/wastebasket', [RoleController::class, 'wastebasket'], [$authMiddleware]);
Route::post('roles/:id/restore', [RoleController::class, 'restore'], [$authMiddleware]);
Route::post('roles/:id/destroy', [RoleController::class, 'destroy'], [$authMiddleware]);
// Rutas comunes
Route::get('roles/:id/edit', [RoleController::class, 'edit'], [$authMiddleware]);
Route::post('roles/:id/update', [RoleController::class, 'update'], [$authMiddleware]);
Route::get('roles/:id/permissions', [RoleController::class, 'permissions'], [$authMiddleware]);
Route::post('roles/:id/permissions', [RoleController::class, 'updatePermissions'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('roles/:id/delete', [RoleController::class, 'delete'], [$authMiddleware]);

/** Rutas para Usuarios */
Route::get('usuarios', [UserController::class, 'index'], [$authMiddleware]);
Route::get('usuarios/create', [UserController::class, 'create'], [$authMiddleware]);
Route::post('usuarios', [UserController::class, 'store'], [$authMiddleware]);
// Ver el listado de la papelera (GET)
Route::get('usuarios/wastebasket', [UserController::class, 'wastebasket'], [$authMiddleware]);
Route::post('usuarios/:id/restore', [UserController::class, 'restore'], [$authMiddleware]);
Route::post('usuarios/:id/destroy', [UserController::class, 'destroy'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('usuarios/:id/delete', [UserController::class, 'delete'], [$authMiddleware]);
// Rutas comunes
Route::get('usuarios/:id/edit', [UserController::class, 'edit'], [$authMiddleware]);
Route::post('usuarios/:id/update', [UserController::class, 'update'], [$authMiddleware]);
Route::get('usuarios/:id/roles', [UserController::class, 'roles'], [$authMiddleware]);
Route::post('usuarios/:id/roles', [UserController::class, 'updateRoles'], [$authMiddleware]);

/** Rutas para Menús */
Route::get('menus', [MenuController::class, 'index'], [$authMiddleware]);
Route::post('menus/get_menu_ajax', [MenuController::class, 'get_menu_ajax'], [$authMiddleware]);
Route::post('menus/guardar_orden_ajax', [MenuController::class, 'guardar_orden_ajax'], [$authMiddleware]);
Route::post('menus/store', [MenuController::class, 'store'], [$authMiddleware]);
Route::post('menus/:id/edit', [MenuController::class, 'edit'], [$authMiddleware]);
Route::post('menus/update', [MenuController::class, 'update'], [$authMiddleware]);
Route::post('menus/delete/:id', [MenuController::class, 'delete'], [$authMiddleware]);
Route::get('menus/papelera', [MenuController::class, 'papelera'], [$authMiddleware]);
Route::post('menus/restore/:id', [MenuController::class, 'restore'], [$authMiddleware]);
Route::post('menus/destroy/:id', [MenuController::class, 'destroy'], [$authMiddleware]);

/** Rutas para niveles */ 
Route::get('niveles', [NivelController::class, 'index'], [$authMiddleware]); 
Route::get('niveles/create', [NivelController::class, 'create'], [$authMiddleware]); 
Route::post('niveles', [NivelController::class, 'store'], [$authMiddleware]); 
Route::get('niveles/:id/datos-ajax', [NivelController::class, 'obtenerDatosAjax'], [$authMiddleware]);
Route::get('niveles/:id/edit', [NivelController::class, 'edit'], [$authMiddleware]); 
Route::post('niveles/:id/actualizar-ajax', [NivelController::class, 'actualizarDatosAjax'], [$authMiddleware]);
Route::post('niveles/:id/update', [NivelController::class, 'update'], [$authMiddleware]); 
Route::post('niveles/:id/delete', [NivelController::class, 'delete'], [$authMiddleware]); 

/** Rutas para subniveles */ 
Route::get('subniveles', [SubnivelController::class, 'index'], [$authMiddleware]); 
// 💡 1. RUTA CRÍTICA MIGRADA AQUÍ: Debe ir antes de cualquier parámetro ":id" para evitar colisiones
Route::get('subniveles/tabla-html', [SubnivelController::class, 'obtenerTablaHtml'], [$authMiddleware]); 
// 2. Rutas basadas en comodines dinámicos (Evaluadas de forma secundaria)
Route::post('subniveles', [SubnivelController::class, 'store'], [$authMiddleware]); 
Route::get('subniveles/:id/datos-ajax', [SubnivelController::class, 'obtenerDatosAjax'], [$authMiddleware]);
Route::get('subniveles/:id/edit', [SubnivelController::class, 'edit'], [$authMiddleware]); 
Route::post('subniveles/:id/update', [SubnivelController::class, 'update'], [$authMiddleware]); 
Route::post('subniveles/:id/delete', [SubnivelController::class, 'delete'], [$authMiddleware]); 

/** Rutas para cursos */
Route::get('cursos', [CursoController::class, 'index'], [$authMiddleware]);          // Listar todos
// 💡 REGISTRAR ANTES de las rutas con parámetros dinámicos (:id)
Route::get('cursos/tabla-html', [CursoController::class, 'tablaHtml'], [$authMiddleware]);
Route::post('cursos', [CursoController::class, 'store'], [$authMiddleware]);         // Crear
Route::get('cursos/:id', [CursoController::class, 'obtenerDatosAjax'], [$authMiddleware]);
Route::post('cursos/:id/update', [CursoController::class, 'update'], [$authMiddleware]); // Actualizar (vía POST)
Route::post('cursos/:id/delete', [CursoController::class, 'delete'], [$authMiddleware]); // Eliminar (vía POST)

/** Rutas para asignaturas */

// Despachador global de la aplicación
Route::dispatch();