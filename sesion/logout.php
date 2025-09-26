<?php
    session_start();
    include '../db/db.php';
    $user_session = $_SESSION['id'];
    $user = $_SESSION['usern'];
        if (!isset($user_session)){
            header("location:../index.php");
        };
    $id_user = $_SESSION['id'];
    $accion = 'El usuario '.$user.' ha cerrado sesion';
    $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
    if(!$bitacora){
        die("Query Failed");
    }
    session_unset();
    session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="relative max-w-md w-full">
        <!-- Contenedor del login -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden z-10 relative">
            <!-- Encabezado con gradiente amarillo -->
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 py-8 px-10 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-white p-3 rounded-full shadow-lg floating">
                        <i class="fas fa-lock text-yellow-500 text-3xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white">Sesion Cerrada con exito</h1>
                <p class="text-yellow-100 mt-1">Precione el boton para volver al login</p>
            </div>
            <!-- Formulario -->
            <div class="px-8 py-8">
                <div>
                    <a href="login.php">
                        <button type="submit" class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition">Login</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/main.js">
    </script>
    <?php 
        include '../public/footer.html';
    ?>
</body>
</html>