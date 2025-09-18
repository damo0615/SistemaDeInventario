<?php
    
    function limpiar_cadena($cadena){
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = str_ireplace("'", "", $cadena);
        $cadena = str_ireplace('"', "", $cadena);
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $longitud = strlen($cadena);
        return $cadena;
    }
?>
