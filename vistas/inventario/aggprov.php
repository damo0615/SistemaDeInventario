<?php
    session_start();
    include '..\php_inyec.php';
    include '../../sesion_time.php';
    include '..\..\db\db.php';
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }else{
        echo "<script>console.log(".$user_id.")</script>";
    }
    if(!empty($_POST['send']) && !empty($_POST['name-prov'])&& !empty($_POST['cod-prov'])&& !empty($_POST['dir-prov']&& !empty($_POST['obser-prov']))){
        $name = limpiar_cadena($_POST['name-prov']);
        $codigo = limpiar_cadena($_POST['cod-prov']);
        $dir = limpiar_cadena($_POST['dir-prov']);
        $obser = limpiar_cadena($_POST['obser-prov']);
        $query = mysqli_query($conn, "INSERT INTO proveedor (nombrep, codigop, direccion, observacion) VALUES ('$name','$codigo','$dir','$obser')");
        if(!$query){
            die("Query Failed");
        }
        $id_user = $_SESSION['id'];
        $accion = 'El usuario '.$_SESSION['usern'].' ha registrado un nuevo proveedor';
        $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
        if(!$bitacora){
            die("Query Failed");
        }
        header('location:proveedores.php');
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
    <link rel="stylesheet" type="text/css" href="../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../../public/navbar.php'; ?>
    <div id="add-product-modal" class="inset-0 ">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Agregar Nuevo Proveedor
                        </h3>
                    </div>
                    <div class="mt-2">
                        <form action="aggprov.php" method="POST">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    </div>
                                    <input type="text" maxlength="25" name="name-prov" id="product-price" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    </div>
                                    <input type="text" maxlength="25" name="cod-prov" id="product-price" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                </div>
                            </div>
                            <div class="col-span-6">
                                <label for="product-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Direccion</label>
                                <input type="text" maxlength="25" name="dir-prov" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            
                            <div class="col-span-6">
                                <label for="product-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                                <textarea id="product-description" name="obser-prov" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                    <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Proveedor"></input>
                    <a href="proveedores.php">
                        <button type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">                        Cancelar
                    </button>
                    </a>
                </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <script src="../../js/main.js"></script>
</body>
<?php include '../../public/footer.html'; ?>
</html>