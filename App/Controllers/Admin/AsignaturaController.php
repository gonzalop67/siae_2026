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

        // Captura el término de búsqueda desde la URL (?buscar=texto)
        $buscar = $_GET['buscar'] ?? '';

        // Obtiene las asignaturas filtradas o completas
        $asignaturas = $this->asignaturaModel->obtenerAsignaturasPlanas($buscar);

        // Colección plana de áreas para el combo
        $areas = $this->areaModel->orderBy('id', 'ASC')->get();

        // Pasa también la variable 'buscar' a la vista para mantener el texto en el input
        return $this->view('admin.asignaturas.index', compact('title', 'areas', 'asignaturas', 'buscar'));
    }

    /**
     * Guarda una asignatura y retorna una respuesta JSON limpia.
     */
    public function store()
    {
        // 1. Forzar a que la respuesta del servidor sea interpretada siempre como JSON
        header('Content-Type: application/json; charset=utf-8');

        // Inicializar el arreglo de respuesta estandarizado
        $respuesta = [
            'ok' => false,
            'success' => false,
            'mensaje' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $respuesta['mensaje'] = 'Método de petición no permitido.';
            echo json_encode($respuesta);
            exit;
        }

        try {
            // 2. Capturar y sanitizar las entradas enviadas en el FormData
            $area_id = filter_input(INPUT_POST, 'area_id', FILTER_VALIDATE_INT);
            $nombre  = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $codigo  = trim(filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_SPECIAL_CHARS));

            // Evaluar el switch de Bootstrap 4 (si no viene marcado, se le asigna 0)
            $estado  = isset($_POST['estado']) ? 1 : 0;

            // 3. Validación de campos obligatorios
            if (!$area_id) {
                $respuesta['mensaje'] = 'Debe seleccionar un área válida.';
                echo json_encode($respuesta);
                exit;
            }

            if (empty($nombre)) {
                $respuesta['mensaje'] = 'El nombre de la asignatura es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            if (empty($codigo)) {
                $respuesta['mensaje'] = 'El código de la asignatura es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            // 🔥 VALIDACIÓN DE DUPLICADOS INTEGRADA
            // Llama a tu método exists() pasando la columna, el valor y el ID opcional
            if ($this->asignaturaModel->exists('codigo', $codigo)) {
                $respuesta['mensaje'] = "El código '{$codigo}' ya se encuentra asignado a otra asignatura.";
                echo json_encode($respuesta);
                exit;
            }

            // Datos a insertar conforme a la estructura de la base de datos
            $datos = [
                'area_id' => $area_id,
                'nombre'  => $nombre,
                'codigo'  => strtoupper($codigo), // Se recomienda forzar mayúsculas para códigos (ej: MAT-01)
                'estado'  => $estado
            ];

            // 4. Ejecutar la inserción usando el modelo correspondiente
            $exito = $this->asignaturaModel->create($datos);

            if ($exito) {
                $respuesta['ok'] = true;
                $respuesta['success'] = true;
                $respuesta['mensaje'] = 'La asignatura ha sido registrada correctamente.';
            } else {
                $respuesta['mensaje'] = 'Hubo un problema interno al intentar guardar la asignatura.';
            }
        } catch (\Exception $e) {
            // Capturar errores imprevistos (base de datos caída, sintaxis, etc.) sin romper el JS
            $respuesta['mensaje'] = 'Error crítico en el servidor: ' . $e->getMessage();
        }

        // 5. Transformar el arreglo PHP a cadena de texto JSON y cerrar la ejecución
        echo json_encode($respuesta);
        exit;
    }

    /**
     * Actualiza una asignatura existente y retorna una respuesta JSON limpia.
     */
    public function update()
    {
        // 1. Forzar a que la respuesta del servidor sea interpretada siempre como JSON
        header('Content-Type: application/json; charset=utf-8');

        // Inicializar el arreglo de respuesta estandarizado
        $respuesta = [
            'ok' => false,
            'success' => false,
            'mensaje' => ''
        ];

        // Para actualizaciones vía formularios tradicionales con adjuntos, se suele validar POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $respuesta['mensaje'] = 'Método de petición no permitido.';
            echo json_encode($respuesta);
            exit;
        }

        try {
            // 🔥 CAPTURA CRÍTICA: Identificador de la asignatura a modificar
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                $respuesta['mensaje'] = 'El identificador de la asignatura no es válido o no fue proporcionado.';
                echo json_encode($respuesta);
                exit;
            }

            // 2. Capturar y sanitizar las entradas enviadas en el FormData
            $area_id = filter_input(INPUT_POST, 'area_id', FILTER_VALIDATE_INT);
            $nombre  = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $codigo  = trim(filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_SPECIAL_CHARS));

            // Evaluar el switch de Bootstrap 4 (si no viene marcado, se le asigna 0)
            $estado  = isset($_POST['estado']) ? 1 : 0;

            // 3. Validación de campos obligatorios
            if (!$area_id) {
                $respuesta['mensaje'] = 'Debe seleccionar un área válida.';
                echo json_encode($respuesta);
                exit;
            }

            if (empty($nombre)) {
                $respuesta['mensaje'] = 'El nombre de la asignatura es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            if (empty($codigo)) {
                $respuesta['mensaje'] = 'El código de la asignatura es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            // Forzar formato estándar para códigos (ej: MAT-01)
            $codigo = strtoupper($codigo);

            // 🔥 VALIDACIÓN DE DUPLICADOS EXCLUSIVA PARA EDICIÓN
            // Se envía el $id actual para que el método exists() excluya este registro de la búsqueda
            if ($this->asignaturaModel->exists('codigo', $codigo, $id)) {
                $respuesta['mensaje'] = "El código '{$codigo}' ya se encuentra asignado a otra asignatura.";
                echo json_encode($respuesta);
                exit;
            }

            // Datos a actualizar conforme a la estructura de la base de datos
            $datos = [
                'area_id' => $area_id,
                'nombre'  => $nombre,
                'codigo'  => $codigo,
                'estado'  => $estado
            ];

            // 🔥 4. Ejecutar la actualización usando el método correspondiente del modelo
            $exito = $this->asignaturaModel->update($id, $datos);

            if ($exito) {
                $respuesta['ok'] = true;
                $respuesta['success'] = true;
                $respuesta['mensaje'] = 'La asignatura ha sido actualizada correctamente.';
            } else {
                $respuesta['mensaje'] = 'Hubo un problema interno o no se detectaron cambios al intentar actualizar la asignatura.';
            }
        } catch (\Exception $e) {
            // Capturar errores imprevistos (base de datos caída, sintaxis, etc.) sin romper el JS
            $respuesta['mensaje'] = 'Error crítico en el servidor: ' . $e->getMessage();
        }

        // 5. Transformar el arreglo PHP a cadena de texto JSON y cerrar la ejecución
        echo json_encode($respuesta);
        exit;
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
                'mensaje' => 'ID de área no válido para eliminación.'
            ];
        }

        // 1. Verificar si la asignatura realmente existe antes de intentar borrarla
        $asignaturaExistente = $this->asignaturaModel->find($id);
        if (!$asignaturaExistente) {
            return [
                'ok' => false,
                'mensaje' => 'La asignatura que intenta eliminar no existe o ya fue borrada.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->asignaturaModel->beginTransaction();

            // Ejecución del método estructurado para borrar por ID
            $this->asignaturaModel->delete($id);

            $this->asignaturaModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'La asignatura fue eliminada con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->asignaturaModel->rollBack();

            // 🔥 CAPTURA AVANZADA DE RESTRICCIÓN DE CLAVE FORÁNEA (Integridad Referencial)
            // Evaluamos el código de error estándar de SQL (23000), el código interno de MySQL (1451)
            // o la presencia de la frase clave en el mensaje de la excepción.
            $codigoError = $e->getCode();
            $mensajeError = $e->getMessage();

            if (
                $codigoError == 23000 ||
                $codigoError == 1451 ||
                strpos($mensajeError, '1451') !== false ||
                strpos($mensajeError, 'foreign key constraint fails') !== false
            ) {
                return [
                    'ok' => false,
                    'mensaje' => 'No se puede eliminar la asignatura porque tiene asignaturas u otros registros relacionados en el sistema.'
                ];
            }

            // Si es cualquier otro tipo de error (ej: pérdida de conexión)
            return [
                'ok' => false,
                'mensaje' => 'Error crítico al procesar la baja: ' . $mensajeError
            ];
        }
    }
}
