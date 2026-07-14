<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Nivel;
use App\Models\Admin\Subnivel;

class SubnivelController extends Controller
{
    protected Nivel $nivelModel;
    protected Subnivel $subnivelModel;

    public function __construct()
    {
        parent::__construct();
        $this->nivelModel = new Nivel;
        $this->subnivelModel = new Subnivel;
    }

    /**
     * Muestra el listado principal de subniveles y precarga los datos del modal.
     */
    public function index()
    {
        $title = 'Lista de Subniveles de Educación';

        // 1. Estructura jerárquica (Padres > Hijos) para el componente asíncrono de la tabla
        $subniveles = $this->nivelModel->obtenerConSubniveles();

        // 2. Colección plana de niveles para alimentar el combo del formulario híbrido
        $niveles = $this->nivelModel->orderBy('id', 'ASC')->get();

        // 3. Renderizar la vista principal enviando ambas variables
        echo $this->view('admin.subniveles.index', compact('title', 'subniveles', 'niveles'));
        exit;
    }

    /**
     * Devuelve exclusivamente el HTML de la tabla de subniveles mediante AJAX.
     */
    public function obtenerTablaHtml()
    {
        // 1. Consultar la estructura actualizada utilizando tu modelo
        $subniveles = $this->nivelModel->obtenerConSubniveles();

        // 2. Definir cabecera limpia para fragmentos HTML parciales
        header('Content-Type: text/html; charset=UTF-8');

        // 3. Retornar el string del parcial compilado por MiniBlade
        return $this->view('admin.subniveles.partials.tabla', compact('subniveles'));
    }

    /**
     * Devuelve los datos del nivel en formato JSON para consumo AJAX.
     */
    public function obtenerDatosAjax(string $id)
    {
        $subnivel = $this->subnivelModel->find((int)$id);
        if (!$subnivel) {
            return ['ok' => false, 'mensaje' => 'Subnivel no encontrado.'];
        }
        return ['ok' => true, 'data' => $subnivel];
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // 1. Capturar inputs de forma segura
        $input = $_POST ?? [];
        $errores = [];
        $nombre = trim($input['nombre'] ?? '');
        $nivelId = !empty($input['nivel_id']) ? (int)$input['nivel_id'] : null;

        // 2. Validaciones de reglas de negocio
        if (empty($nivelId)) {
            $errores['nivel_id'] = 'Debe seleccionar un nivel educativo padre.';
        }

        if (empty($nombre)) {
            $errores['nombre'] = 'El campo nombre del subnivel es obligatorio.';
        } elseif (strlen($nombre) < 3 || strlen($nombre) > 64) { // 💡 HOMOLOGADO A 64 CARACTERES COMO EN JS
            $errores['nombre'] = 'El nombre debe tener entre 3 y 64 caracteres.';
        }

        // Si hay fallos de validación, retornamos los errores estructurados para crear.js
        if (!empty($errores)) {
            return [
                'ok' => false,
                'errors' => $errores
            ];
        }

        try {
            // 3. Calcular el secuencial de orden para los hijos de este nivel específico
            $sqlOrden = "SELECT COALESCE(MAX(orden), 0) + 1 AS siguiente 
                     FROM subniveles_educativos 
                     WHERE nivel_id = ? AND deleted_at IS NULL";

            $this->subnivelModel->query($sqlOrden, [$nivelId], 'i');

            // Adaptado asumiendo un método de extracción seguro en tu base de datos o modelo
            $resOrden = $this->subnivelModel->getQueryResult()->fetch_assoc();
            $siguienteOrden = (int)($resOrden['siguiente'] ?? 1);

            // 4. Preparar el set de datos para la persistencia
            $datos = [
                'nivel_id' => $nivelId,
                'nombre'   => $nombre,
                'orden'    => $siguienteOrden
            ];

            // 5. Persistencia segura con transacciones
            $this->subnivelModel->beginTransaction();

            $this->subnivelModel->create($datos);

            $this->subnivelModel->commit();

            // Retorno limpio capturado por tu enrutador para transformarlo en JSON
            return [
                'ok' => true,
                'mensaje' => 'El subnivel educativo fue registrado con éxito.'
            ];
        } catch (\Throwable $e) {
            $this->subnivelModel->rollBack();

            return [
                'ok' => false,
                'mensaje' => 'Error inesperado en el servidor: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Muestra los datos de un recurso específico para edición.
     * @param string|int $id El ID es inyectado desde el enrutador
     */
    public function edit($id)
    {
        $id = (int)$id;

        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID no válido para consulta.'
            ];
        }

        try {
            // Buscar el subnivel utilizando el ORM de tu modelo
            $subnivel = $this->subnivelModel->find($id);

            if (!$subnivel) {
                return [
                    'ok' => false,
                    'mensaje' => 'El subnivel no existe o fue eliminado del sistema.'
                ];
            }

            // Simplemente retornas el array. Route::dispatch() lo detecta y lo envía como JSON
            return [
                'ok' => true,
                'data' => $subnivel
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'mensaje' => 'Error al recuperar información: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     * @param string|int $id El ID es inyectado desde el enrutador
     */
    public function update($id)
    {
        $id = (int)$id;

        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de subnivel no válido para actualización.'
            ];
        }

        // Capturar datos sanitizados del $_POST enviado por el FormData de JS
        $input = $_POST ?? [];
        $nombre = trim($input['nombre'] ?? '');
        $nivelId = !empty($input['nivel_id']) ? (int)$input['nivel_id'] : null;

        // Validación de reglas de negocio
        $errores = [];
        if (empty($nivelId)) {
            $errores['nivel_id'] = 'Debe seleccionar un nivel educativo padre.';
        }

        if (empty($nombre)) {
            $errores['nombre'] = 'El campo nombre del subnivel es obligatorio.';
        } elseif (strlen($nombre) < 3 || strlen($nombre) > 64) { // 💡 HOMOLOGADO A 64 CARACTERES COMO EN JS
            $errores['nombre'] = 'El nombre debe tener entre 3 y 64 caracteres.';
        }

        // Si hay fallos de validación, retornamos los errores estructurados para crear.js
        if (!empty($errores)) {
            return [
                'ok' => false,
                'errors' => $errores
            ];
        }

        // Verificar existencia previa
        $subnivelExistente = $this->subnivelModel->find($id);
        if (!$subnivelExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El subnivel que intenta actualizar no existe.'
            ];
        }

        $datos = [
            'nivel_id' => $nivelId,
            'nombre'   => $nombre,
        ];

        try {
            $this->subnivelModel->beginTransaction();

            // Ejecución del query estructurado en tu framework
            $this->subnivelModel->update($id, $datos);

            $this->subnivelModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El subnivel educativo fue actualizado con éxito.'
            ];
        } catch (\Throwable $e) {
            $this->subnivelModel->rollBack();
            return [
                'ok' => false,
                'mensaje' => 'Error crítico en la base de datos: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Realiza la eliminación lógica (Soft Delete) del subnivel.
     * @param string|int $id El ID es inyectado desde el enrutador
     */
    public function delete($id)
    {
        $id = (int)$id;

        // Validar que el ID sea correcto
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de subnivel no válido.'
            ];
        }

        try {
            // En tu arquitectura, el Soft Delete actualiza la columna de estado o fecha de eliminación
            $eliminado = $this->subnivelModel->delete($id);

            if ($eliminado) {
                // Retorno limpio capturado por tu enrutador para transformarlo en JSON
                return [
                    'ok' => true,
                    'mensaje' => 'El subnivel educativo ha sido eliminado correctamente.'
                ];
            } else {
                return [
                    'ok' => false,
                    'mensaje' => 'No se encontró el registro o ya fue eliminado previamente.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'mensaje' => 'Error interno en el servidor: ' . $e->getMessage()
            ];
        }
    }
}
