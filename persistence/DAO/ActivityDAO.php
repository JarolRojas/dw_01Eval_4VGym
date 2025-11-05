<?php

require_once(__DIR__ . '/../conf/DBConfig.php');
require_once(__DIR__ . '/../../app/models/Activity.php');

class ActivityDAO
{
    private $conn;

    const ACTIVITY_TABLE = 'activities';

    public function __construct()
    {
        $this->conn = DBConfig::getConnection();
    }


    /**
     * Obtiene todas las actividades, opcionalmente filtradas por fecha
     *
     * @param string|null $date Fecha en formato 'Y-m-d' para filtrar actividades
     * @return array Lista de actividades
     */
    public function getAll($date = null)
    {
        $activities = [];
        $query = "SELECT id, type, monitor, place, date FROM activities";

        if ($date) {
            $dateFilter = date('Y-m-d', strtotime($date));
            $query .= " WHERE DATE(date) = '$dateFilter'";
        }

        $query .= " ORDER BY date DESC";

        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $activity = new Activity(
                    $row['id'],
                    $row['type'],
                    $row['monitor'],
                    $row['place'],
                    $row['date']
                );
                $activities[] = $activity;
            }
        }

        return $activities;
    }


    /**
     * Inserta una nueva actividad en la base de datos
     * @param Activity $activity Objeto Activity a insertar
     * @return bool Resultado de la operación
     */
    public function insert(Activity $activity)
    {
        $type = $activity->getType();
        $monitor = $activity->getMonitor();
        $place = $activity->getPlace();
        $date = $activity->getDate();

        $query = "INSERT INTO activities (type, monitor, place, date) VALUES ('$type', '$monitor', '$place', '$date')";

        $stmt = mysqli_prepare($this->conn, $query);
        return $stmt->execute();
    }

    /**
     * Actualiza una actividad existente en la base de datos
     * @param Activity $activity Objeto Activity a actualizar
     * @return bool Resultado de la operación
     */
    public function update(Activity $activity)
    {
        $id = (int) $activity->getId();
        $type = $activity->getType();
        $monitor = $activity->getMonitor();
        $place = $activity->getPlace();
        $date = $activity->getDate();

        $query = "UPDATE activities 
                SET type = '$type', monitor = '$monitor', place = '$place', date = '$date'
                WHERE id = $id";
        $stmt = mysqli_prepare($this->conn, $query);
        return $stmt->execute();
    }

    /**
     * Elimina una actividad por su ID
     * @param int $id ID de la actividad a eliminar
     * @return bool Resultado de la operación
     */
    public function delete($id)
    {
        $id = (int) $id;
        $query = "DELETE FROM activities WHERE id = $id";
        $stmt = mysqli_prepare($this->conn, $query);
        return $stmt->execute();
    }
}
?>