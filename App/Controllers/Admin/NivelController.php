<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Nivel;

class NivelController extends Controller
{
    protected Nivel $nivelModel;

    public function __construct()
    {
        parent::__construct();
        $this->nivelModel = new Nivel;
    }

    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Listado de Niveles de Educación';
        $niveles = $this->nivelModel->orderBy('id')->get();

        return $this->view('admin.niveles.index', compact('niveles', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear Nivel de Educación';

        return $this->view('admin.niveles.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // 1. Capturar datos directamente de $_POST
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->nivelModel->validate($input)) {
            header('Content-Type: application/json');
            header('HTTP/1.1 422 Unprocessable Entity'); // Código de estado estándar para errores de validación
            echo json_encode([
                'ok' => false,
                'errors' => $this->nivelModel->errors
            ]);
            exit; // Detiene la ejecución para asegurar que no se envíe nada más
        }

        // 3. Preparación del set de datos
        $datos = [
            'nombre' => trim($input['nombre'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones
        try {
            $this->nivelModel->beginTransaction();

            $this->nivelModel->create($datos);

            $this->nivelModel->commit();

            header('Content-Type: application/json');
            echo json_encode([
                'ok' => true,
                'mensaje' => 'Nivel Educativo procesado con éxito.'
            ]);
            exit;
        } catch (\Throwable $e) {
            $this->nivelModel->rollBack();

            header('Content-Type: application/json');
            header('HTTP/1.1 500 Internal Server Error'); // Código para fallas de base de datos o excepciones
            echo json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Devuelve los datos del nivel en formato JSON para consumo AJAX.
     */
    public function obtenerDatosAjax(string $id)
    {
        $nivel = $this->nivelModel->find((int)$id);
        if (!$nivel) {
            return ['ok' => false, 'mensaje' => 'Nivel no encontrado.'];
        }
        return ['ok' => true, 'data' => $nivel];
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit(int $id)
    {
        $nivel = $this->nivelModel->find($id);
        $title = "Editar Nivel Educativo";

        return $this->view('admin.niveles.edit', compact('title', 'nivel'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(int $id)
    {
        // 1. Capturar datos directamente de $_POST
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->nivelModel->validate($input, $id)) {
            header('Content-Type: application/json');
            header('HTTP/1.1 422 Unprocessable Entity'); // Código de estado estándar para errores de validación
            echo json_encode([
                'ok' => false,
                'errors' => $this->nivelModel->errors
            ]);
            exit; // Detiene la ejecución para asegurar que no se envíe nada más
        }

        // 3. Preparación del set de datos
        $datos = [
            'nombre' => trim($input['nombre'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones
        try {
            $this->nivelModel->beginTransaction();

            $this->nivelModel->update($id, $datos);

            $this->nivelModel->commit();

            header('Content-Type: application/json');
            echo json_encode([
                'ok' => true,
                'mensaje' => 'Nivel Educativo procesado con éxito.'
            ]);
            exit;
        } catch (\Throwable $e) {
            $this->nivelModel->rollBack();

            header('Content-Type: application/json');
            header('HTTP/1.1 500 Internal Server Error'); // Código para fallas de base de datos o excepciones
            echo json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function delete(int $id)
    {
        header('Content-Type: application/json');

        try {
            $eliminado = $this->nivelModel->delete($id);

            if ($eliminado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El registro ha sido eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontró el registro o ya fue eliminado.'
                ]);
            }
        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar el registro porque tiene registros relacionados en otras tablas'
            ]);
        }
        exit; // Detiene la ejecución para que solo devuelva el JSON
    }

    /**
     * Procesa la actualización del nivel educativo principal mediante AJAX.
     * @param string|int $id Inyectado desde el enrutador
     */
    public function actualizarDatosAjax($id)
    {
        $id = (int)$id;

        if ($id <= 0) {
            return ['ok' => false, 'mensaje' => 'ID de nivel no válido.'];
        }

        // Capturar inputs enviados por el formulario
        $input = $_POST ?? [];
        $nombre = trim($input['nombre'] ?? '');

        // Validaciones básicas de negocio
        $errores = [];
        if (empty($nombre)) {
            $errores['nombre'] = 'El campo nombre del nivel es obligatorio.';
        } elseif (strlen($nombre) < 3 || strlen($nombre) > 64) {
            $errores['nombre'] = 'El nombre del nivel debe tener entre 3 y 64 caracteres.';
        }

        // Si hay errores de formato, regresamos la lista estructurada para el frontend
        if (!empty($errores)) {
            return ['ok' => false, 'errors' => $errores];
        }

        // Verificar existencia en la base de datos
        $nivelExistente = $this->nivelModel->find($id);
        if (!$nivelExistente) {
            return ['ok' => false, 'mensaje' => 'El nivel educativo no existe o fue eliminado.'];
        }

        try {
            $this->nivelModel->beginTransaction();

            // Actualizamos usando el ORM de tu framework
            $this->nivelModel->update($id, ['nombre' => $nombre]);

            $this->nivelModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El nivel educativo principal ha sido actualizado con éxito.'
            ];
        } catch (\Throwable $e) {
            $this->nivelModel->rollBack();
            return [
                'ok' => false,
                'mensaje' => 'Error crítico en la base de datos: ' . $e->getMessage()
            ];
        }
    }
}
