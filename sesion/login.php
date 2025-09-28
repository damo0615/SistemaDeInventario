<?php
    include '../db/db.php';
    include '../db/config.php';
    include '../vistas/php_inyec.php';
    session_start();
    $actual= time();

    if(isset($_POST['send'])&& !empty($_POST['send'])){
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (!verificarRecaptcha($recaptchaResponse)) {
            $_SESSION['mensaje_error'] = 'Por favor, verifique que no es un robot';
         header('Location: login.php');
        exit;
        }
        if(!empty($_POST['user'])){
            $user = limpiar_cadena($_POST['user']);
            $password = limpiar_cadena($_POST['password']);
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
                                $_SESSION['mensaje_error'] = "Ha alcanzado el limite de intentos, recupere su contraseña o comuniquese con un admin";
                                $save = $intento = 3-$_SESSION['intento'];
                                $query = mysqli_query($conn, "UPDATE usuario SET estatus=0 WHERE username='$name'");
                                if(!$query){
                                    die("Query Failed UPDATE");
                                }
                            }else{
                                $intento = 3-$_SESSION['intento'];
                                $_SESSION['mensaje_error'] = "Contraseña errada te quedan ".$intento." intentos";
                            }
                        }
                    }else{
                        $_SESSION['mensaje_error'] = "El usuario esta bloqueado, contacte con un administrador";
                    }
                }
            }else{ 
                $_SESSION['mensaje_error'] = "Usuario no encontrado";            }
    
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            <?php if (isset($_SESSION['mensaje_error'])): ?>
                <div class="error-message">
                    <div class="message error">
                        <?php echo $_SESSION['mensaje_error'];?>
                    </div>                    
                </div>
            <?php endif; ?>       
            <!-- Formulario -->
            <div class="px-8 py-8">
                <form id="loginForm" class="space-y-6" method="POST" action="login.php" autocomplete="off">
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-solid fa-user text-yellow-500"></i>
                            </div>
                            <input type="text" maxlength="25" id="user" name="user" required 
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
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
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
        const closeBtn = document.getElementById('closeBtn');

        toggleButton.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);

        });
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    <?php 
                        unset($_GET['error']);
                        unset($_SESSION['mensaje_error']);
                    ?>
                });
            }
        });
    </script>
    <?php 
    include '../public/footer.html';
?>
</body>
</html>