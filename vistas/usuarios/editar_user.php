 <?php
    session_start();
    include '..\php_inyec.php';
    include '../../sesion_time.php';
    include '..\..\db\db.php';
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $roles = mysqli_query($conn, "SELECT Nombre,id FROM permisos");
    if(!empty($_POST['send'])){
        $nombre = limpiar_cadena($_POST['name']);
        $dni = $_POST['dni'];
        $username = $_POST['username'];
        $_SESSION['usern'] = $username;
        $email = $_POST['email'];
        
        $verfi = mysqli_query($conn, "SELECT id,username,email FROM usuario WHERE username='$username' OR email='$email' ");
                    if($verfi-> num_rows >= 0){
                            $user= $verfi->fetch_assoc();
                            $userid = $user['id'];
                            $query = mysqli_query($conn, "UPDATE usuario SET nombres='$nombre',dni='$dni',username='$username',email='$email' WHERE id='$userid'");
                            if(!$query){
                                die("Query Failed");
                            }
                            $id_user = $_SESSION['id'];
                            $accion = 'El usuario '.$_SESSION['usern'].' ha editado su usuario';
                            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                            if(!$bitacora){
                                die("Query Failed");
                            }
                            header('location:../usuario.php');
                    }else{
                        echo "<script>window.alert('Usuario o Correo ya registrado, Intente de nuevo')</script>";
                    }
    }
                                    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                            Editar Usuario
                        </h3>
                    </div>
                    <div class="mt-2">
                        <form action="editar_user.php" method="POST">
                            <?php    
                                if (isset($_GET['edit'])){
                                    $user = $_GET['edit'];
                                    $update_query = mysqli_query($conn, "SELECT * FROM usuario WHERE id='$user'");

                                    if (mysqli_num_rows($update_query) > 0) {
                                    while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                                     ?>
                        <div class="grid grid-cols-6 gap-8">
                            <div class="col-span-6">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Usuario</label>
                                <input type="text" value="<?php echo $fetch_update['nombres'] ?>" name="name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            <div class="col-span-6">
                                <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI del Usuario</label>
                                <input type="number" maxlength="25" value="<?php echo $fetch_update['dni'] ?>" name="dni" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            <div class="col-span-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email del Usuario</label>
                                <input type="email" value="<?php echo $fetch_update['email'] ?>" name="email" id="user-email" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                <input type="text" maxlength="25" value="<?php echo $fetch_update['username'] ?>" name="username" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>                         
                            
                            
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                            <input type="submit" id="save-product" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Actualizar Usuario"></input>
                            <a href="alluser.php">
                                <button type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">Cancelar
                            </button>
                            </a>
                        </div>
                    </div>
                    <?php
                        }}}
                    ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../js/main.js"></script>
</body>
<?php include '../../public/footer.html'; ?>
</html>
