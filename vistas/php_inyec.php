<?php
    
    function limpiar_cadena($cadena){
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = str_ireplace("'", "", $cadena);
        $cadena = str_ireplace('"', "", $cadena);
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $longitud = strlen($cadena);
        if (!empty($cadena)) {
            return $cadena;
        }else{
            $_SESSION['mensaje_sql'] = "INGRESE UN VALOR VALIDO AL REGISTRO";
        }
    }
?>
