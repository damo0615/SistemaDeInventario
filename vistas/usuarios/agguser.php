<?php
    session_start();
    include '..\php_inyec.php';
    include '../../sesion_time.php';
    include '..\..\db\db.php';
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    function encrypt($string, $key)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
    $roles = mysqli_query($conn, "SELECT Nombre,id FROM permisos");
    if(!empty($_POST['send'])){
        $nombre = limpiar_cadena($_POST['name']);
        $dni = limpiar_cadena($_POST['dni']);
        $username = limpiar_cadena($_POST['username']);
        $email = limpiar_cadena($_POST['email']);
        $password = limpiar_cadena($_POST['password']);
        $c_password = limpiar_cadena($_POST['c_password']);
        $rol = limpiar_cadena($_POST['rol']);
        $pre1 = limpiar_cadena($_POST['pre1']);
        $res1 = limpiar_cadena($_POST['res1']);
        $pre2 = limpiar_cadena($_POST['pre2']);
        $res2 = limpiar_cadena($_POST['res2']);
        $random = 12;
        $opcion = array("cost"=>$random);        
        if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{3,}$/", $password)){
                if($c_password == $password){
                    $clave = password_hash($password, PASSWORD_BCRYPT, $opcion);
                    $prep1= encrypt($pre1,$keyconfi);
                    $prep2= encrypt($pre2,$keyconfi);
                    $resp1 = password_hash($res1, PASSWORD_BCRYPT, $opcion);
                    $resp2 = password_hash($res2, PASSWORD_BCRYPT, $opcion);
                    $verfi = mysqli_query($conn, "SELECT * FROM usuario WHERE username='$username' OR email='$email'");
                    $nr = mysqli_num_rows($verfi);
                    if($nr == 0){
                            $query = mysqli_query($conn, "INSERT INTO usuario (nombres,dni,username,email,password,id_permiso,estatus,pregunta1,respuesta1,pregunta2,respuesta2) VALUES ('$nombre','$dni','$username','$email','$clave','$rol',0,'$prep1','$resp1','$prep2','$resp2')");

                            if(!$query){
                                die("Query Failed");
                            }
                            $id_user = $_SESSION['id'];
                            $accion = 'El usuario '.$_SESSION['usern'].' ha creado un nuevo usuario';
                            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                            if(!$bitacora){
                                die("Query Failed");
                            }
                            header('location:alluser.php');   
                    }else{
                        echo "<script>window.alert('Usuario o Correo ya registrado, Intente de nuevo')</script>";
                    }
                }
        }else{
            echo "<script>window.alert('La contraseña debe tener al menos un numero y un caracter especial')</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=chrome">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../../public/navbar.php'; ?>
    <div id="add-product-modal" class="inset-0 ">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-10">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Agregar Nuevo Usuario
                        </h3>
                    </div>
                    <div class="mt-2">
                        <form action="agregar_usuario.php" method="POST">
                        <div class="grid grid-cols-6 gap-8">
                            <div class="col-span-6">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Usuario</label>
                                <input type="text" maxlength="25" name="name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>
                            <div class="col-span-6">
                                <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI del Usuario</label>
                                <input type="number" maxlength="25" name="dni" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="numero">
                            </div>
                            <div class="col-span-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email del Usuario</label>
                                <input type="email" name="email" id="user-email" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="email">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                <input type="text" maxlength="25" name="username" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol de Usuario</label>
                                <select id="product-category" name="rol" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                    <?php
                                        if($roles -> num_rows > 0){
                                            while($name = $roles->fetch_assoc()){
                                                    $nombres = $name['Nombre'];
                                                    $id = $name['id'];
                                    ?>  
                                                    <option value="<?php echo $id;?>"><?php echo $nombres;?></option>
                                    <?php
                                                }
                                            }
                                    ?>
                                </select>
                            </div>
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                                <input type="password" name="password" id="pwd" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500 dark:text-white" required minlength="16">
                                <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" onclick="pwd.type = this.checked ? 'text' : 'password'" />
                            </div>                    
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirme la Contraseña</label>
                                <input type="password" name="c_password" id="pwd1" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required minlength="16">
                                <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" onclick="pwd1.type = this.checked ? 'text' : 'password'" />
                            </div>
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pregunta de Seguridad 1:</label>
                                <input type="text" maxlength="25" name="pre1" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Respuesta 1:</label>
                                <input type="text" maxlength="25" name="res1"  class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pregunta de Seguridad 2:</label>
                                <input type="text" maxlength="25" name="pre2" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>
                            <div class="col-span-5">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Respuesta 2:</label>
                                <input type="text" maxlength="25" name="res2"  class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales">
                            </div>
                           
                            
                            
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                            <input type="submit" id="save-product" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Usuario"></input>
                            <a href="alluser.php">
                                <button type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">Cancelar
                            </button>
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <script src="../../js/main.js"></script>
    <script src="../../js/validador.js"></script>
</body>
</html>