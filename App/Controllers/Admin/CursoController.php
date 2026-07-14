<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Curso;
use App\Models\Admin\Nivel;
use App\Models\Admin\Subnivel;

class CursoController extends Controller
{
    protected Curso $cursoModel;
    protected Nivel $nivelModel;
    protected Subnivel $subnivelModel;

    public function __construct()
    {
        parent::__construct();
        $this->cursoModel = new Curso;
        $this->nivelModel = new Nivel;
        $this->subnivelModel = new Subnivel;
    }

    /**
     * Muestra el listado principal de subniveles y sus cursos, precargando el modal.
     */
    public function index()
    {
        $title = 'Gestión de Cursos por Subnivel';

        // 1. Estructura jerárquica (Subniveles > Cursos) para la tabla/componente asíncrono
        $subnivelesConCursos = $this->subnivelModel->obtenerConCursos();

        // 2. Colección plana de subniveles para alimentar el combo (select) del formulario del modal
        $subnivelesPlanos = $this->subnivelModel->orderBy('id', 'ASC')->get();

        // 3. Colección plana de niveles para alimentar el combo (select) del formulario del modal
        $nivelesPlanos = $this->nivelModel->orderBy('id', 'ASC')->get();

        // 3. Renderizar enviando la jerarquía para la tabla y la lista plana para el select
        echo $this->view('admin.cursos.index', compact('title', 'subnivelesConCursos', 'subnivelesPlanos', 'nivelesPlanos'));
        exit;
    }

    /**
     * Endpoint AJAX: Devuelve únicamente el HTML de la tabla de cursos actualizada.
     * GET /cursos/tabla-html
     */
    public function tablaHtml()
    {
        // 1. Volver a consultar la estructura jerárquica actualizada desde la Base de Datos
        $subnivelesConCursos = $this->subnivelModel->obtenerConCursos();

        // 2. Renderizar ÚNICAMENTE el archivo parcial (sin el layout general de la app)
        // Usamos el 'exit' de tu arquitectura para cortar la ejecución y enviar solo este bloque HTML
        echo $this->view('admin.cursos.partials.tabla', compact('subnivelesConCursos'));
        exit;
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
        $subnivelId = !empty($input['subnivel_id']) ? (int)$input['subnivel_id'] : null;
        // 💡 CORRECCIÓN: Se mantiene como STRING porque es un campo ENUM en MySQL
        $seccion    = !empty($input['seccion']) ? trim($input['seccion']) : '';

        // 2. Validaciones de reglas de negocio
        if (empty($subnivelId)) {
            $errores['subnivel_id'] = 'Debe seleccionar un subnivel educativo padre.';
        }

        if (empty($seccion)) {
            $errores['seccion'] = 'Debe seleccionar una sección/horario.';
        } elseif (!in_array($seccion, ['Matutina', 'Vespertina', 'Nocturna'])) {
            // 💡 VALIDACIÓN EXTRA: Validar que sea un valor ENUM permitido
            $errores['seccion'] = 'La sección seleccionada no es válida.';
        }

        if (empty($nombre)) {
            $errores['nombre'] = 'El campo nombre del curso es obligatorio.';
        } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
            // 💡 ADVERTENCIA: Tu base de datos soporta hasta 100 caracteres (VARCHAR(100))
            $errores['nombre'] = 'El nombre debe tener entre 3 y 100 caracteres.';
        }

        // Si hay fallos de validación, retornamos los errores estructurados para crear.js
        if (!empty($errores)) {
            return [
                'ok' => false,
                'errors' => $errores
            ];
        }

        try {
            // 3. Calcular el secuencial de orden para los hijos de este subnivel específico
            $sqlOrden = "SELECT COALESCE(MAX(orden), 0) + 1 AS siguiente 
                     FROM cursos 
                     WHERE subnivel_id = ?";

            $this->cursoModel->query($sqlOrden, [$subnivelId], 'i');

            // Adaptado asumiendo un método de extracción seguro en tu base de datos o modelo
            $resOrden = $this->cursoModel->getQueryResult()->fetch_assoc();
            $siguienteOrden = (int)($resOrden['siguiente'] ?? 1);

            // 4. Preparar el set de datos para la persistencia
            $datos = [
                'subnivel_id' => $subnivelId,
                'nombre'      => $nombre,
                'seccion'     => $seccion,
                'orden'       => $siguienteOrden
            ];

            // 5. Persistencia segura con transacciones
            $this->cursoModel->beginTransaction();

            $this->cursoModel->create($datos);

            $this->cursoModel->commit();

            // Retorno limpio capturado por tu enrutador para transformarlo en JSON
            return [
                'ok' => true,
                'mensaje' => 'El curso fue registrado con éxito.'
            ];
        } catch (\Throwable $e) {
            $this->cursoModel->rollBack();

            return [
                'ok' => false,
                'mensaje' => 'Error inesperado en el servidor: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Devuelve los datos del curso en formato JSON para consumo AJAX.
     */
    public function obtenerDatosAjax(string $id)
    {
        $curso = $this->cursoModel->find((int)$id);
        if (!$curso) {
            return ['ok' => false, 'mensaje' => 'Curso no encontrado.'];
        }
        return ['ok' => true, 'data' => $curso];
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
                'mensaje' => 'ID de curso no válido para actualización.'
            ];
        }

        // Capturar datos sanitizados del $_POST enviado por el FormData de JS
        $input = $_POST ?? [];
        $nombre     = trim($input['nombre'] ?? '');
        $subnivelId = !empty($input['subnivel_id']) ? (int)$input['subnivel_id'] : null;
        // 💡 MANTENIDO COMO STRING: Es un campo ENUM en tu base de datos de cursos
        $seccion    = !empty($input['seccion']) ? trim($input['seccion']) : '';

        // Validación de reglas de negocio
        $errores = [];
        if (empty($subnivelId)) {
            $errores['subnivel_id'] = 'Debe seleccionar un subnivel educativo padre.';
        }

        if (empty($seccion)) {
            $errores['seccion'] = 'Debe seleccionar una sección/horario.';
        } elseif (!in_array($seccion, ['Matutina', 'Vespertina', 'Nocturna'])) {
            $errores['seccion'] = 'La sección seleccionada no es válida.';
        }

        if (empty($nombre)) {
            $errores['nombre'] = 'El campo nombre del curso es obligatorio.';
        } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
            // 💡 ADAPTADO: Tu esquema SQL soporta hasta VARCHAR(100) para cursos
            $errores['nombre'] = 'El nombre debe tener entre 3 y 100 caracteres.';
        }

        // Si hay fallos de validación, retornamos los errores estructurados
        if (!empty($errores)) {
            return [
                'ok' => false,
                'errors' => $errores
            ];
        }

        // Verificar existencia previa en el modelo de cursos
        $cursoExistente = $this->cursoModel->find($id);
        if (!$cursoExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El curso que intenta actualizar no existe.'
            ];
        }

        // Preparar el set de datos para la persistencia de la tabla cursos
        $datos = [
            'subnivel_id' => $subnivelId,
            'nombre'      => $nombre,
            'seccion'     => $seccion
        ];

        try {
            $this->cursoModel->beginTransaction();

            // Ejecución del query estructurado de actualización
            $this->cursoModel->update($id, $datos);

            $this->cursoModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El curso fue actualizado con éxito.'
            ];
        } catch (\Throwable $e) {
            $this->cursoModel->rollBack();

            // Captura elegante si se intenta asociar a un subnivel_id inexistente en MySQL (Error 1452 / 23000)
            if (strpos($e->getMessage(), '1452') !== false || $e->getCode() == 23000) {
                return [
                    'ok' => false,
                    'mensaje' => 'El subnivel educativo seleccionado no es válido o ya no existe.'
                ];
            }

            return [
                'ok' => false,
                'mensaje' => 'Error crítico en la base de datos: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Elimina permanentemente un recurso específico de la base de datos.
     * @param string|int $id El ID es inyectado desde el enrutador
     */
    public function delete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de curso no válido para eliminación.'
            ];
        }

        // 1. Verificar si el curso realmente existe antes de intentar borrarlo
        $cursoExistente = $this->cursoModel->find($id);
        if (!$cursoExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El curso que intenta eliminar no existe o ya fue borrado.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->cursoModel->beginTransaction();

            // Ejecución del método estructurado de tu framework para borrar por ID
            $this->cursoModel->delete($id);

            $this->cursoModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El curso fue eliminado con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->cursoModel->rollBack();

            // Captura opcional en caso de que existan restricciones de clave foránea descendientes
            if (strpos($e->getMessage(), '1451') !== false || $e->getCode() == 23000) {
                return [
                    'ok' => false,
                    'mensaje' => 'No se puede eliminar el curso porque tiene registros relacionados en el sistema.'
                ];
            }

            return [
                'ok' => false,
                'mensaje' => 'Error crítico al procesar la baja: ' . $e->getMessage()
            ];
        }
    }
}
