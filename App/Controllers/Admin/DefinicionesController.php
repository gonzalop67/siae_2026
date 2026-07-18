<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Parcial;
use App\Models\Admin\PeriodoLectivo;
use App\Models\Admin\PeriodoAcademico;

class DefinicionesController extends Controller
{
    protected Parcial $parcialModel;
    protected PeriodoLectivo $lectivoModel;
    protected PeriodoAcademico $academicoModel;

    public function __construct()
    {
        parent::__construct();
        $this->parcialModel = new Parcial;
        $this->lectivoModel = new PeriodoLectivo;
        $this->academicoModel = new PeriodoAcademico;
    }

    public function index()
    {
        $title = 'Panel Periodos Académicos';

        return $this->view('admin.panel_periodos.index', compact('title'));
    }

    /**
     * Helper para retornar respuestas JSON limpias
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Retorna todos los años lectivos configurados
     */
    public function getLectivos()
    {
        $lectivos = $this->lectivoModel->orderBy('fecha_inicio', 'DESC')->get();

        return $this->jsonResponse($lectivos);
    }

    /**
     * Procesa y guarda un nuevo periodo lectivo desde el modal
     * POST /configuracion/periodos/guardar-lectivo
     */
    public function storeLectivo()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Validación blindada limpiando espacios en blanco
        if (
            !$data ||
            empty(trim($data['nombre'] ?? '')) ||
            empty(trim($data['fecha_inicio'] ?? '')) ||
            empty(trim($data['fecha_fin'] ?? ''))
        ) {
            return $this->jsonResponse(['error' => 'Campos obligatorios incompletos'], 400);
        }

        $insertData = [
            'nombre'       => trim($data['nombre']),
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin'],
            'estado'       => 1 // Activo por defecto
        ];

        // Tu método create() que usa el array masivo $fillable
        $nuevoRegistro = $this->lectivoModel->create($insertData);

        if ($nuevoRegistro) {
            return $this->jsonResponse($nuevoRegistro, 201);
        }

        return $this->jsonResponse(['error' => 'Error interno al registrar en la base de datos'], 500);
    }

    /**
     * Retorna los bloques académicos de un periodo lectivo
     * GET api/periodos-academicos/:id
     */
    public function getAcademicosPorLectivo($id)
    {
        if (!$id) {
            return $this->jsonResponse([]);
        }

        // El $id llega limpio gracias a tu enrutador Core\Route
        $bloques = $this->academicoModel->where('periodo_lectivo_id', $id)
            ->orderBy('orden', 'ASC')
            ->get();

        return $this->jsonResponse($bloques);
    }

    /**
     * Retorna los parciales de evaluación filtrados por bloque
     * GET api/parciales/:id
     */
    public function getParcialesPorAcademico($id)
    {
        if (!$id) {
            return $this->jsonResponse([]);
        }

        $parciales = $this->parcialModel->where('periodo_academico_id', $id)
            ->orderBy('orden', 'ASC')
            ->get();

        return $this->jsonResponse($parciales);
    }

    /**
     * Guarda un nuevo periodo académico (Trimestre/Bimestre)
     * POST /configuracion/periodos/guardar-academico
     */
    public function storeAcademico()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || empty($data['nombre']) || empty($data['periodo_lectivo_id']) || empty($data['tipo'])) {
            return $this->jsonResponse(['error' => 'Campos obligatorios incompletos'], 400);
        }

        $nuevoRegistro = $this->academicoModel->create($data);

        if ($nuevoRegistro) {
            return $this->jsonResponse($nuevoRegistro, 201);
        }

        return $this->jsonResponse(['error' => 'Error al guardar el bloque académico'], 500);
    }

    /**
     * Guarda un nuevo parcial de evaluación
     * POST /configuracion/periodos/guardar-parcial
     */
    public function storeParcial()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Corregir nombre del campo de cierre proveniente del formulario si es necesario
        if (isset($data['fecha_cierre_notes'])) {
            $data['fecha_cierre_notes'] = $data['fecha_cierre_notes'];
        }

        if (!$data || empty($data['nombre']) || empty($data['periodo_academico_id']) || empty($data['peso_nota'])) {
            return $this->jsonResponse(['error' => 'Campos obligatorios incompletos'], 400);
        }

        $nuevoRegistro = $this->parcialModel->create($data);

        if ($nuevoRegistro) {
            return $this->jsonResponse($nuevoRegistro, 201);
        }

        return $this->jsonResponse(['error' => 'Error al guardar el parcial'], 500);
    }

    /**
     * Actualiza un periodo lectivo existente
     * POST /configuracion/periodos/editar-lectivo/:id
     */
    public function updateLectivo($id)
    {
        // 1. Capturar el JSON enviado por el Fetch de JavaScript
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Validación estricta de seguridad
        if (!$id || !$data || empty(trim($data['nombre'] ?? ''))) {
            return $this->jsonResponse(['error' => 'Datos insuficientes para la actualización'], 400);
        }

        // 3. Limpiar y estructurar la información para la base de datos
        $updateData = [
            'nombre'       => trim($data['nombre']),
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin']
        ];

        try {
            // Ejecuta la actualización pasándole el ID y el array de datos modificados
            // (Si tu modelo base usa otra palabra como save() o where()->update(), adáptalo aquí)
            $actualizado = $this->lectivoModel->update($id, $updateData);

            if ($actualizado) {
                return $this->jsonResponse([
                    'success' => true,
                    'mensaje' => 'El período lectivo se actualizó correctamente en el sistema.'
                ]);
            }

            // Si el usuario presionó guardar sin cambiar ningún texto, el motor SQL puede devolver 0 filas afectadas
            return $this->jsonResponse([
                'success' => true,
                'mensaje' => 'No se detectaron cambios nuevos para guardar.'
            ]);
        } catch (\Throwable $e) {
            // Captura cualquier error de SQL de forma segura y lo devuelve como JSON para que no rompa el Fetch
            return $this->jsonResponse([
                'error' => 'Error en la base de datos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un bloque académico existente (Trimestre / Bimestre)
     * POST /configuracion/periodos/editar-academico/:id
     */
    public function updateAcademico($id)
    {
        // 1. Capturar el JSON enviado por el Fetch de JavaScript
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Validación estricta de seguridad
        if (!$id || !$data || empty(trim($data['nombre'] ?? ''))) {
            return $this->jsonResponse(['error' => 'Datos insuficientes para la actualización'], 400);
        }

        // 3. Limpiar y estructurar la información para la base de datos
        // Nota: Mapeamos los campos exactos del formulario para la tabla periodos_academicos
        $updateData = [
            'nombre'       => trim($data['nombre']),
            'tipo'         => trim($data['tipo']),
            'orden'        => (int)$data['orden'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin']
        ];

        try {
            // Ejecuta la actualización pasándole el ID y el array estructurado
            // Cambia $this->academicoModel por el nombre exacto de tu propiedad si varía
            $actualizado = $this->academicoModel->update($id, $updateData);

            if ($actualizado) {
                return $this->jsonResponse([
                    'success' => true,
                    'mensaje' => 'El bloque académico se actualizó correctamente en el sistema.'
                ]);
            }

            // Si el usuario presionó guardar sin cambiar ningún texto, el motor SQL puede devolver 0 filas afectadas
            return $this->jsonResponse([
                'success' => true,
                'mensaje' => 'No se detectaron cambios nuevos para guardar.'
            ]);
        } catch (\Throwable $e) {
            // Captura cualquier error de SQL de forma segura y lo devuelve como JSON para que no rompa el Fetch
            return $this->jsonResponse([
                'error' => 'Error en la base de datos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un parcial de evaluación existente
     * POST /configuracion/periodos/editar-parcial/:id
     */
    public function updateParcial($id)
    {
        // 1. Capturar el JSON de JavaScript
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // 2. Validación de seguridad
        if (!$id || !$data || empty(trim($data['nombre'] ?? ''))) {
            return $this->jsonResponse(['error' => 'Datos insuficientes'], 400);
        }

        // 3. Estructurar información mapeando los campos exactos del $fillable de tu modelo Parcial
        $updateData = [
            'nombre'             => trim($data['nombre']),
            'peso_nota'          => (int)$data['peso_nota'],
            'orden'              => (int)$data['orden'],
            'fecha_inicio'       => $data['fecha_inicio'],
            'fecha_fin'          => $data['fecha_fin'],
            'fecha_cierre_notas' => $data['fecha_cierre_notas']
        ];

        try {
            // Usamos la propiedad del modelo inyectada en tu constructor
            // Ejecuta el UPDATE con cláusula WHERE id = $id automáticamente
            $actualizado = $this->parcialModel->update($id, $updateData);

            if ($actualizado) {
                return $this->jsonResponse([
                    'success' => true,
                    'mensaje' => 'El parcial de evaluación se actualizó correctamente.'
                ]);
            }

            return $this->jsonResponse([
                'success' => true,
                'mensaje' => 'No se detectaron modificaciones nuevas en el parcial.'
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse([
                'error' => 'Error en la base de datos',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un periodo lectivo y sus dependencias en cascada
     * POST /configuracion/periodos/eliminar-lectivo/:id
     */
    public function destroyLectivo($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de periodo lectivo no válido para eliminación.'
            ];
        }

        // 1. Verificar si el periodo lectivo realmente existe antes de intentar borrarlo
        $lectivoExistente = $this->lectivoModel->find($id);
        if (!$lectivoExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El periodo lectivo que intenta eliminar no existe o ya fue borrado.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->lectivoModel->beginTransaction();

            // Ejecución del método estructurado para borrar por ID
            $this->lectivoModel->delete($id);

            $this->lectivoModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El periodo lectivo fue eliminado con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->lectivoModel->rollBack();

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
                    'mensaje' => 'No se puede eliminar el periodo lectivo porque tiene periodos académicos u otros registros relacionados en el sistema.'
                ];
            }

            // Si es cualquier otro tipo de error (ej: pérdida de conexión)
            return [
                'ok' => false,
                'mensaje' => 'Error crítico al procesar la baja: ' . $mensajeError
            ];
        }
    }

    /**
     * Elimina un bloque académico
     * POST /configuracion/periodos/eliminar-academico/:id
     */
    public function destroyAcademico($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de periodo académico no válido para eliminación.'
            ];
        }

        // 1. Verificar si el periodo académico realmente existe antes de intentar borrarlo
        $academicoExistente = $this->academicoModel->find($id);
        if (!$academicoExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El periodo académico que intenta eliminar no existe o ya fue borrado.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->academicoModel->beginTransaction();

            // Ejecución del método estructurado para borrar por ID
            $this->academicoModel->delete($id);

            $this->academicoModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El periodo académico fue eliminado con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->lectivoModel->rollBack();

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
                    'mensaje' => 'No se puede eliminar el periodo académico porque tiene parciales de evaluación u otros registros relacionados en el sistema.'
                ];
            }

            // Si es cualquier otro tipo de error (ej: pérdida de conexión)
            return [
                'ok' => false,
                'mensaje' => 'Error crítico al procesar la baja: ' . $mensajeError
            ];
        }
    }

    /**
     * Elimina un parcial de evaluación
     * POST /configuracion/periodos/eliminar-parcial/:id
     */
    public function destroyParcial($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'ID de parcial de evaluación no válido para eliminación.'
            ];
        }

        // 1. Verificar si el periodo de evaluación realmente existe antes de intentar borrarlo
        $parcialExistente = $this->parcialModel->find($id);
        if (!$parcialExistente) {
            return [
                'ok' => false,
                'mensaje' => 'El periodo de evaluación que intenta eliminar no existe o ya fue borrado.'
            ];
        }

        try {
            // 2. Ejecutar la baja de forma segura mediante transacciones
            $this->parcialModel->beginTransaction();

            // Ejecución del método estructurado para borrar por ID
            $this->parcialModel->delete($id);

            $this->parcialModel->commit();

            return [
                'ok' => true,
                'mensaje' => 'El periodo de evaluación fue eliminado con éxito de la plataforma.'
            ];
        } catch (\Throwable $e) {
            $this->lectivoModel->rollBack();

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
                    'mensaje' => 'No se puede eliminar el periodo de evaluación porque tiene calificaciones u otros registros relacionados en el sistema.'
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
