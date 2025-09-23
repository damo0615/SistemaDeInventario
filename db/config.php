<?php
    define('RECAPTCHA_SITE_KEY', '6Le96tIrAAAAANMMRqcRojdsSVDlJE5UmonRTvRw');
define('RECAPTCHA_SECRET_KEY', '6Le96tIrAAAAAK_aYBZPRE3amT8rGcFl1SPr7P7n');





function verificarRecaptcha($recaptchaResponse) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptchaResponse
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);
    
    return $response->success;
}

// Función para conectar a la base de datos
function conectarDB() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    return $conexion;
}
?>