<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Area;
use App\Models\Admin\Asignatura;

class AsignaturaController extends Controller
{
    protected Area $areaModel;
    protected Asignatura $asignaturaModel;

    public function __construct()
    {
        parent::__construct();
        $this->areaModel = new Area;
        $this->asignaturaModel = new Asignatura;
    }

    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Gestión de Estructura Curricular';

        // 1. Obtiene el listado completo de asignaturas con sus áreas vinculadas
        $asignaturas = $this->asignaturaModel->obtenerAsignaturasPlanas();

        // 2. Colección plana de áreas (activas) para alimentar el combo (select) del formulario de Asignaturas
        $areas = $this->areaModel->where('estado', 1)->orderBy('id', 'ASC')->get();

        // Envía todas las variables necesarias a la vista
        return $this->view('admin.asignaturas.index', compact('title', 'areas', 'asignaturas'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear AsignaturasController';
        // return $this->view('admin.asignaturas.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // $this->model->create($_POST);
        // return redirect('/asignaturas');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show($id)
    {
        // $data = $this->model->find($id);
        // return $this->view('admin.asignaturas.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar AsignaturasController';
        // $data = $this->model->find($id);
        // return $this->view('admin.asignaturas.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
        // $this->model->update($id, $_POST);
        // return redirect('/asignaturas');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        // $this->model->delete($id);
        // return redirect('/asignaturas');
    }
}
