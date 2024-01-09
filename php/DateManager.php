<?php

class DateManager {
    function toDMY($inputDate) {
        $dateTime = DateTime::createFromFormat('Y-m-d', $inputDate);

        if (!$dateTime) {
            return false;
        }

        return $dateTime->format('d-m-Y');
    }

    function toYMD($inputDate) {
        $dateTime = DateTime::createFromFormat('d-m-Y', $inputDate);

        if (!$dateTime) {
            return false;
        }
        
        return $dateTime->format('d-m-Y');
    }
}

?>
