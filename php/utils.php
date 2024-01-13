<?php
class Sanitizer{
    public static function trimValues(&$value){
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

    public static function SanitizeWord(&$value){
        $value=preg_replace('/[^\p{L}-]/u',"",$value);
        return $value;
    }

    public static function ValidateDate($value){
        return preg_match('/$\d{4}-\d{2}-d{2}^/',$value);
    }
    public static function SanitizeDate(&$value){
        $value=preg_replace('/[^\w\s-]/','',$value);
        return $value;
    }
    public static function Sanitize(&$value){
        /*Elimina tutti i valori che non siano caratteri o spaziature (comprese lettere accentate)*/
        $value=strip_tags($value);
        if(is_array($value)){
            foreach($value as &$a){
                $a=preg_replace('/[^\s\p{L}-]/u',"",$a);
                $a=trim($a);
            }
        }
        else{
            $value=preg_replace('/[^\s\p{L}-]/u',"",$value);
            $value=trim($value);
        }
        return $value;
            /*  Per eseguire sanificare numeri o mail, basta utilizzare la funzione filter_var($var,FILTER_SANITIZE...).
                Per le stringhe l'opzione FILTER_SANITIZE_STRING è deprecata
                Qualcosa di simile é htmlspecialchars();*/
    }
}

function addPaginator(){
    return "
        <div>
            <div class=\"pagination\">
                <button
                    id=\"prev_pag_button\"
                    aria-label=\"Pagina precedente\"
                    class=\"pagination_controls\"
                    disabled>
                    <svg
                        xmlns=\"http://www.w3.org/2000/svg\"
                        height=\"1em\"
                        width=\"1em\"
                        viewBox=\"0 0 320 512\">
                        <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path
                            fill=\"currentColor\"
                            d=\"M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z\" />
                    </svg>
                </button>
                <div id=\"pagination_numbers\"></div>
                <button
                    id=\"next_pag_button\"
                    aria-label=\"Prossima pagina\"
                    class=\"pagination_controls\"
                    disabled>
                    <svg
                        xmlns=\"http://www.w3.org/2000/svg\"
                        height=\"1em\"
                        width=\"1em\"
                        viewBox=\"0 0 320 512\">
                        <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path
                            fill=\"currentColor\"
                            d=\"M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z\" />
                    </svg>
                </button>
            </div>
            <div class=\"return_top_button\">
                <button id=\"back_to_top\" class=\"button_reverse\">
                    Ritorna sopra
                </button>
            </div>
        </div>
    ";
}

?>