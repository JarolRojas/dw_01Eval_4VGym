<?php

/**
 * Formatea la fecha de una actividad al formato "DIA MES AÑO HORA"
 * Ejemplo: "5 MARZO 2024 14:30"
 * @param string $date Fecha en formato 'Y-m-d H:i:s'
 * @return string Fecha formateada
 */
if (!function_exists('formatActivityDate')) {
    function formatActivityDate($date)
    {
        if (empty($date)) {
            return '';
        }

        try {
            $dt = new DateTime($date);
        } catch (Exception $e) {
            return $date;
        }

        $day = $dt->format('j');
        $monthIndex = (int) $dt->format('n');
        $months = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];

        $month = isset($months[$monthIndex]) ? $months[$monthIndex] : strtoupper($dt->format('F'));
        $year = $dt->format('Y');
        $time = $dt->format('H:i');

        return sprintf('%s %s %s %s', $day, $month, $year, $time);
    }
}
