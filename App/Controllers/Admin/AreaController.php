<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Area;

class AreaController extends Controller
{
    protected Area $areaModel;

    public function __construct()
    {
        parent::__construct();
        $this->areaModel = new Area;
    }

    /**
     * Guarda un área y retorna una respuesta JSON limpia.
     */
    public function store()
    {
        // 1. Forzar a que la respuesta del servidor sea interpretada siempre como JSON
        header('Content-Type: application/json; charset=utf-8');

        // Inicializar el arreglo de respuesta estandarizado
        $respuesta = [
            'ok'      => false,
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
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));

            // Evaluar el switch de Bootstrap 4 (si no viene marcado, se le asigna 0)
            $estado = isset($_POST['estado']) ? 1 : 0;

            // 3. Validación de campos obligatorios
            if (empty($nombre)) {
                $respuesta['mensaje'] = 'El nombre del área es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            // Datos a insertar o actualizar
            $datos = [
                'nombre' => $nombre,
                'estado' => $estado
            ];

            // (Opcional) Aquí podrías validar primero si el nombre ya existe en la BD
            $exito = $this->areaModel->create($datos);

            if ($exito) {
                $respuesta['ok']      = true;
                $respuesta['success'] = true;
                $respuesta['mensaje'] = 'El área ha sido registrada correctamente.';
            } else {
                $respuesta['mensaje'] = 'Hubo un problema interno al intentar guardar el área.';
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
     * Actualiza un área y retorna una respuesta JSON limpia.
     */
    public function update(string $id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de área no válido para eliminación.'
            ];
        }

        // 1. Forzar a que la respuesta del servidor sea interpretada siempre como JSON
        header('Content-Type: application/json; charset=utf-8');

        // Inicializar el arreglo de respuesta estandarizado
        $respuesta = [
            'ok'      => false,
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
            $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));

            // Evaluar el switch de Bootstrap 4 (si no viene marcado, se le asigna 0)
            $estado = isset($_POST['estado']) ? 1 : 0;

            // 3. Validación de campos obligatorios
            if (empty($nombre)) {
                $respuesta['mensaje'] = 'El nombre del área es un campo requerido.';
                echo json_encode($respuesta);
                exit;
            }

            // 4. Determinar si es una actualización (si viene un ID) o una inserción
            if (empty($id)) {
                $respuesta['mensaje'] = 'No se ha pasado correctamente el id del registro a editar.';
                echo json_encode($respuesta);
                exit;
            }

            // Datos a insertar o actualizar
            $datos = [
                'nombre' => $nombre,
                'estado' => $estado
            ];

            // (Opcional) Aquí podrías validar primero si el nombre ya existe en la BD
            $exito = $this->areaModel->update($id, $datos);

            if ($exito) {
                $respuesta['ok']      = true;
                $respuesta['success'] = true;
                $respuesta['mensaje'] = 'El área ha sido actualizada correctamente.';
            } else {
                $respuesta['mensaje'] = 'Hubo un problema interno al intentar actualizar el área.';
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

        // 1. Verificar si el área realmente existe antes de intentar borrarla
        $areaExistente = $this->areaModel->find($id);
        if (!$areaExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El área que intenta eliminar no existe o ya fue borrada.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->areaModel->beginTransaction();

            // Ejecución del método estructurado para borrar por ID
            $this->areaModel->delete($id);

            $this->areaModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El área fue eliminada con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->areaModel->rollBack();

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
                    'mensaje' => 'No se puede eliminar el área porque tiene asignaturas u otros registros relacionados en el sistema.'
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
