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
        $title = "Listado de Usuarios";

        $search = isset($_GET['search']) ? $_GET['search'] : "";

        if ($search !== "") {
            $users = $this->userModel
                ->where('us_fullname', 'LIKE', '%' . $_GET['search'] . '%')
                ->orWhere('us_login', 'LIKE', '%' . $_GET['search'] . '%')
                ->orderBy('us_fullname')
                ->paginate(5);
        } else {
            $users = $this->userModel
                ->orderBy('us_fullname')
                ->paginate(5);
        }

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
