<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Usuario;
use Override;

class UserController extends Controller
{
    protected Usuario $userModel;

    #[Override]
    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new Usuario;
    }

    public function index()
    {
        $title = "Users Admin";
        $search = isset($_GET['search']) ? trim($_GET['search']) : "";

        // Aseguramos limpiar cualquier residuo estructural previo del modelo
        $this->userModel->where = "";
        $this->userModel->values = [];

        // 1. Configuramos el select, el join relacional y el ordenamiento
        $query = $this->userModel
            ->select('usuarios.*', 'personas.nombre_completo')
            ->join('personas', 'usuarios.persona_id', '=', 'personas.id');

        // 2. Aplicamos la búsqueda usando paréntesis explícitos si el usuario escribe algo
        if ($search !== "") {
            $likeSearch = '%' . $search . '%';
            // Calificamos explícitamente las tablas para evitar errores de ambigüedad en el WHERE
            $query->where = "(personas.nombre_completo LIKE ? OR usuarios.username LIKE ?)";
            $query->values = [$likeSearch, $likeSearch];
        }

        // 3. Paginar los resultados obtenidos
        $users = $query->orderBy('personas.nombre_completo', 'ASC')
            ->paginate(5);

        // 🔥 AGREGA ESTA LÍNEA DE PRUEBA:
        // show($users);
        // die();

        return $this->view('admin.usuarios.index', compact('users', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear UserController';
        // return $this->view('admin.user.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // $this->model->create($_POST);
        // return redirect('/user');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show($id)
    {
        // $data = $this->model->find($id);
        // return $this->view('admin.user.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar UserController';
        // $data = $this->model->find($id);
        // return $this->view('admin.user.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
        // $this->model->update($id, $_POST);
        // return redirect('/user');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        // $this->model->delete($id);
        // return redirect('/user');
    }
}
