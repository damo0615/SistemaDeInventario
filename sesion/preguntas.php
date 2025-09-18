<?php
    include '../db/db.php';
    session_start();
    if(!empty($_SESSION['email'])){
        $email = $_SESSION['email'];
        $pregun = mysqli_query($conn, "SELECT id,pregunta1,pregunta2 FROM usuario WHERE email='$email'");
        if($comp = $pregun->fetch_object()){
            $pregunta1 = $comp->pregunta1;
            $pregunta2 = $comp->pregunta2;
            if(isset($_POST['send'])){
                $respu = mysqli_query($conn, "SELECT id,respuesta1,respuesta2,username,id_permiso FROM usuario WHERE email='$email'");
                $res1 = limpiar_cadena($_POST['res1']);
                $res2 = $_POST['res2'];
                if($compe = $respu->fetch_object()){
                    $respu1 = $compe->respuesta1;
                    $respu2 = $compe->respuesta2;
                    if(password_verify($res1,$respu1)){
                        if(password_verify($res2,$respu2)){
                            $_SESSION['id'] = $comp->id;
                            $_SESSION['usern']=$comp->username;
                            $_SESSION['rol']=$comp->id_permiso;
                            header("location:actualizar.php");
                        }else{
                            echo '<script>window.alert("Verfique sus respuesta 2")</script>';
                        }
                    }else{
                        echo '<script>window.alert("Verfique sus respuesta 1")</script>';
                    }
                }
            }
        }else{
            header("location:reset.php");
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
    <link rel="stylesheet" href="../style.css">
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
                <p class="text-yellow-100 mt-1">Responda las siguientes preguntas de seguridad</p>
            </div>            
            <!-- Formulario -->
            <div class="px-8 py-8">
                <form id="loginForm" class="space-y-6" method="POST" action="preguntas.php">
                    <div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta 1: <?php echo '¿'.$pregunta1.'?';?></label>
                            <input type="text" id="user" name="res1" required 
                                   class="input-effect pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-400 focus:outline-none transition">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta 2: <?php echo '¿'.$pregunta2.'?';?></label>
                            <input type="text" id="user" name="res2" required 
                                   class="input-effect pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-400 focus:outline-none transition">
                        </div>
                    </div>                                    
                    <div class="flex items-center justify-between">                       
                        <div class="text-sm">
                            <a href="reset.php" class="font-medium text-yellow-500 hover:text-yellow-600">
                                <i class="fa-solid fa-arrow-left"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                    <div>
                        <input type="submit" 
                                class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition" name="send" value="Verificar Respuestas">
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>