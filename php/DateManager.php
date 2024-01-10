<?php

class DateManager {
    public static function toDMY($inputDate) {
        $dateTime = DateTime::createFromFormat('Y-m-d', $inputDate);

        if (!$dateTime) {
            return false;
        }

        return $dateTime->format('d-m-Y');
    }

    public static function toYMD($inputDate) {
        $dateTime = DateTime::createFromFormat('d-m-Y', $inputDate);

        if (!$dateTime) {
            return false;
        }
        
        return $dateTime->format('d-m-Y');
    }

    public static function toFormattedTimestamp($timestamp) {
        $timestamp_num = strtotime($timestamp);

        // Formatta la data e l'ora come desiderato
        $formattedDateTime = date("d-m-Y H:i", $timestamp_num);
        
        return $formattedDateTime;
    }
}

?>
