<?php

require_once(__DIR__ . '/../models/Activity.php');
require_once(__DIR__ . '/../../persistence/DAO/ActivityDAO.php');

class ActivityController
{
    private $dao;

    /**
     * Constructor de la clase ActivityController
     */
    public function __construct()
    {
        $this->dao = new ActivityDAO();
    }


    /**
     * Obtiene todas las actividades, opcionalmente filtradas por fecha
     *
     * @param string|null $date Fecha en formato 'Y-m-d' para filtrar actividades
     * @return array Lista de actividades
     */
    public function getAll($date = null)
    {
        return $this->dao->getAll($date);
    }


    /**
     * Crea una nueva actividad
     * @param string $type Tipo de actividad
     * @param string $monitor Nombre del monitor
     * @param string $place Lugar de la actividad
     * @param string $date Fecha y hora de la actividad en formato 'Y-m-d H:i:s'
     * @return array Resultado de la operación con éxito o error
     */
    public function create($type, $monitor, $place, $date)
    {
        if (empty($type) || empty($monitor) || empty($place) || empty($date)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        if (!in_array($type, ['spinning', 'bodypump', 'pilates'])) {
            return ['success' => false, 'message' => 'Tipo de actividad no válido'];
        }

        try {
            $dt = new DateTime($date);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Fecha no válida'];
        }

        $now = new DateTime('now');
        if ($dt < $now) {
            return ['success' => false, 'message' => 'La fecha no puede ser anterior a la actual'];
        }

        $dateNormalized = $dt->format('Y-m-d H:i:s');

        $activity = new Activity(null, $type, $monitor, $place, $dateNormalized);
        $id = $this->dao->insert($activity);

        if ($id > 0) {
            return ['success' => true, 'message' => 'Actividad creada correctamente', 'id' => $id];
        } else {
            return ['success' => false, 'message' => 'Error al crear la actividad'];
        }
    }


    /**
     * Actualiza una actividad existente
     * @param int $id ID de la actividad a actualizar
     * @param string $type Tipo de actividad
     * @param string $monitor Nombre del monitor
     * @param string $place Lugar de la actividad
     * @param string $date Fecha y hora de la actividad en formato 'Y-m-d H:i:s'
     * @return array Resultado de la operación con éxito o error
     */
    public function update($id, $type, $monitor, $place, $date)
    {
        if (empty($id) || empty($type) || empty($monitor) || empty($place) || empty($date)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        if (!in_array($type, ['spinning', 'bodypump', 'pilates'])) {
            return ['success' => false, 'message' => 'Tipo de actividad no válido'];
        }
        try {
            $dt = new DateTime($date);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Fecha no válida'];
        }

        $now = new DateTime('now');
        if ($dt < $now) {
            return ['success' => false, 'message' => 'La fecha no puede ser anterior a la actual'];
        }

        $dateNormalized = $dt->format('Y-m-d H:i:s');

        $activity = new Activity($id, $type, $monitor, $place, $dateNormalized);
        if ($this->dao->update($activity)) {
            return ['success' => true, 'message' => 'Actividad actualizada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la actividad'];
        }
    }

    /**
     * Elimina una actividad por su ID
     * @param int $id ID de la actividad a eliminar
     * @return array Resultado de la operación con éxito o error
     */
    public function delete($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'ID de actividad no válido'];
        }

        if ($this->dao->delete($id)) {
            return ['success' => true, 'message' => 'Actividad eliminada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar la actividad'];
        }
    }
}
?>