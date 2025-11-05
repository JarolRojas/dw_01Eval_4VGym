<?php

require_once(__DIR__ . '/../models/Activity.php');
require_once(__DIR__ . '/../../persistence/DAO/ActivityDAO.php');

class ActivityController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new ActivityDAO();
    }


    public function getAll($date = null)
    {
        return $this->dao->getAll($date);
    }


    public function create($type, $monitor, $place, $date)
    {
        if (empty($type) || empty($monitor) || empty($place) || empty($date)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        if (!in_array($type, ['spinning', 'bodypump', 'pilates'])) {
            return ['success' => false, 'message' => 'Tipo de actividad no válido'];
        }

        $activity = new Activity(null, $type, $monitor, $place, $date);
        $id = $this->dao->insert($activity);

        if ($id > 0) {
            return ['success' => true, 'message' => 'Actividad creada correctamente', 'id' => $id];
        } else {
            return ['success' => false, 'message' => 'Error al crear la actividad'];
        }
    }

    public function update($id, $type, $monitor, $place, $date)
    {
        if (empty($id) || empty($type) || empty($monitor) || empty($place) || empty($date)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        if (!in_array($type, ['spinning', 'bodypump', 'pilates'])) {
            return ['success' => false, 'message' => 'Tipo de actividad no válido'];
        }

        $activity = new Activity($id, $type, $monitor, $place, $date);
        if ($this->dao->update($activity)) {
            return ['success' => true, 'message' => 'Actividad actualizada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la actividad'];
        }
    }

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