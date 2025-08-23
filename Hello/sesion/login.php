<?php
    include '../db/db.php';
    session_start();
    $actual= time();
    $secret_key = "6LeTrpIrAAAAAGelw8Qev0wJ6gzhWTcQCdm3o0W7";
    if(isset($_POST['send'])&& !empty($_POST['send'])){
    /* if(isset($_POST['captcha-response']) && !empty($_POST['captcha-response'])){ */
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['captcha-response']);
        $responseData = json_decode($verify);   
        /* if($responseData->success){ */
            if(!empty($_POST['user'])){
            $user = $_POST['user'];
            $password = $_POST['password'];
            $result = mysqli_query($conn, "SELECT * FROM usuario WHERE username='$user'");
            if($comp = $result->fetch_object()){
                $name = $comp->username;
                $hash = $comp->password;
                $rol = $comp->id_permiso;
                $status = $comp->estatus;
                if($user == $name){
                    if ($status == true) {
                        if(password_verify($password, $hash)){
                            $_SESSION['id']=$comp->id;
                            $_SESSION['usern']=$name;
                            $_SESSION['rol']=$rol;
                            $id_user = $_SESSION['id'];
                            $accion = 'El usuario '.$user.' ha iniciado sesion';
                            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                            if(!$bitacora){
                                die("Query Failed");
                            }
                            if ($actual < $tiempo){
                                    $contador = ceil(($tiempo - $actual) / 60);
                                    echo "<script>
                                    alert('Has agotado tus 3 intentos, intentalo de nuevo en $contador minutos');
                                    </script>";
                            }else{
                                header("location:../vistas/dashboard.php");
                            }
                        }else{
                            if(!isset($_SESSION['intento'])){
                                $_SESSION['intento'] = 1;
                            }else {
                                $_SESSION['intento']++;
                            }
                            if($_SESSION['intento'] >= 3){
                                if (!isset($_SESSION['tiempo']) || $_SESSION['tiempo'] == 0) {
                                    $_SESSION['tiempo'] = time() + (1 * 60);
                                }
                                $actual = time();
                                $tiempo = $_SESSION['tiempo'];

                                if ($actual < $tiempo) {
                                    $contador = ceil(($tiempo - $actual) / 60);
                                    echo "<script>
                                    alert('Has agotado tus 3 intentos, intentalo de nuevo en $contador minutos');
                                    </script>";
                                } else {
                                    $_SESSION['intento'] = 0;
                                    $_SESSION['tiempo'] = 0;
                                }
                            }else{
                                $intento = 3-$_SESSION['intento'];
                                echo '<script>window.alert("Contraseña errada te quedan '.$intento.' intentos")</script>';
                            }
                        }
                    }else{
                        echo '<script>window.alert("El usuario esta bloqueado, contacte con un administrador")</script>';
                    }
                }
            }else{ 
                echo '<script>window.alert("Usuario no encontrado")</script>';
            }
      /*   } */
    }
       /*  } */
        
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        var onloadCallback = function(){
            grecaptcha.execute();
        };
        function setResponse(response) {
            document.getElementById("captcha-response").value = response;
        };
    </script>
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
                <h1 class="text-2xl font-bold text-white">Bienvenido de nuevo</h1>
                <p class="text-yellow-100 mt-1">Inicia sesión para continuar</p>
            </div>            
            <!-- Formulario -->
            <div class="px-8 py-8">
                <form id="loginForm" class="space-y-6" method="POST" action="login.php">
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-solid fa-user text-yellow-500"></i>
                            </div>
                            <input type="text" id="user" name="user" required 
                                   class="input-effect pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-400 focus:outline-none transition"
                                   placeholder="Tu nombre de usuario">
                        </div>
                    </div>                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-yellow-500"></i>
                            </div>
                            <input type="password" id="password" name="password" required 
                                   class="input-effect pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-400 focus:outline-none transition"
                                   placeholder="••••••••" id="password">
                            <input type="hidden" id="captcha-response" name="captcha-response">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="togglePassword">
                                <i id="eyeIcon" class="fas fa-eye-slash text-yellow-500"></i>
                            </div>
                        </div>
                    </div>                 
                    <div class="flex items-center justify-between">                       
                        <div class="text-sm">
                            <a href="reset.php" class="font-medium text-yellow-500 hover:text-yellow-600">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>
                    <div class="form-item">
                        <!-- Google reCAPTCHA widget -->
                        <div class="g-recaptcha"
                            data-sitekey="6LeTrpIrAAAAACOTi5VGoTUpkTV-U02ny8VUZxT8"
                            data-badge="inline"
                            data-size="invisible"
                            data-callback="setResponse">
                        </div>
                    </div>
                    <div>
                        <input type="submit" 
                                class="btn-glow w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition"  name="send" value="Iniciar Sesion">
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <script src="main.js">
    </script>
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');

        toggleButton.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);

        });
    </script>
</body>
</html>