<?php
    include '../db/db.php';
    session_start();
    if(!empty($_POST['email'])){
        if(isset($_POST['send'])){
            $email = $_POST['email'];
            $result = mysqli_query($conn, "SELECT * FROM usuario WHERE email='$email'");
            if($comp = $result->fetch_object()){
                $_SESSION['email'] = $email;
                header("location:preguntas.php");
            }else{ 
                echo '<script>window.alert("Usuario no encontrado")</script>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="\img\icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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
                <h1 class="text-2xl font-bold text-white">Bienvenido al modulo de Recuperacion</h1>
                <p class="text-yellow-100 mt-1">Ingrese su correo para continuar</p>
            </div>            
            <!-- Formulario -->
            <div class="px-8 py-8">
                <form id="loginForm" class="space-y-6" method="POST" action="reset.php">
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Correo Electronico (Email)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-solid fa-user text-yellow-500"></i>
                            </div>
                            <input type="email" id="user" name="email" required 
                                   class="input-effect pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-400 focus:outline-none transition"
                                   placeholder="ejemplo@ejemplo.com">
                        </div>
                    </div>                                    
                    <div class="flex items-center justify-between">                       
                        <div class="text-sm">
                            <a href="login.php" class="font-medium text-yellow-500 hover:text-yellow-600">
                                Iniciar Sesion
                            </a>
                        </div>
                    </div>
                    <div>
                        <input type="submit" 
                                class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition" name="send" value="Verificar Correo">
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>