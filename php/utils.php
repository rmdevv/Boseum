<?php
class Sanitizer{
    public function trimValues(&$value){
        if(is_array($value)){
            foreach($value as &$a){
                $a=trim($a);
            }
        }
        else{
            $value=trim($value);
        }
        return $value;
    }
    public function Sanitize(&$value){
        $value=strip_tags($value);
        if(is_array($value)){
            foreach($value as &$a){
                $a=preg_replace('/[^\w\s]/',"",$a);
                $a=trim($a);
            }
        }
        else{
            $value=preg_replace('/[^\w\s]/',"",$value);
            $value=trim($value);
        }
        return $value;
            /*  Per eseguire sanificare numeir o mail, basta utilizzare la funzione filter_var($var,FILTER_SANITIZE...).
                Per le stringhe l'opzione FILTER_SANITIZE_STRINGb è deprecata
                Qualcosa di simile é htmlspecialchars();
                */
    }
}
?>
