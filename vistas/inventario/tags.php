<?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $query = mysqli_query($conn, "SELECT nombres,id FROM tag ");
    if(isset($_POST['send'])){
        $nombre = $_POST['name'];
        $query1 = mysqli_query($conn, "INSERT INTO tag (nombres) VALUES ('$nombre')");
        if(!$query1){
            die("Query Failed");
        }
        $id_user = $_SESSION['id'];
        $accion = 'El usuario '.$_SESSION['usern'].' ha registrado una nueva etiqueta';
        $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
        if(!$bitacora){
            die("Query Failed");
        }
        header('location:tags.php');
    }
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nombre = $_POST['name'];
        $query = mysqli_query($conn, "UPDATE tag set nombres='$nombre' WHERE id='$id'");
        if(!$query){
            die("Query Failed");
        }
        $id_user = $_SESSION['id'];
        $accion = 'El usuario '.$_SESSION['usern'].' ha actualizado una etiqueta';
        $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
        if(!$bitacora){
            die("Query Failed");
        }
        header('location:tags.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <nav class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-boxes text-primary-500 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Inventory Pro</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="../dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="../caja.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Caja
                        </a>
                        <a href="../inventario.php" class="border-primary-500 text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inventario
                        </a>
                        <a href="../reporte.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Reportes
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="mobile-menu-button" type="button" class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5 mr-1">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ml-3 relative">
                        <div>
                            <a href="../usuario.php">
                                <button type="button" class="bg-white dark:bg-gray-800 rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">AD</div>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Men� m�vil -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="../dashboard.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Dashboard
                </a>
                <a href="../inventario.php" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:bg-gray-700 dark:text-primary-400">
                    Inventario
                </a>
                <a href="../reporte.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Reportes
                </a>
            </div>
        </div>
    </nav>
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                <form action="tags.php" method="POST">
                            <input type="text" name="name" class="border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white-500 hover:bg-white-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white-500" required>
                            <input type="submit" name="send" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" value="Agregar">
                        </form><?php
                                }
                        ?>
                        
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <a href="../inventario.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Productos
                            </button>
                        </a>
                        <a href="proveedores.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Proveedores
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de Etiquetas -->
    <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Listado Total de Etiquetas
            </h3>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <?php
                if($query->num_rows > 0){
                    while($tag = $query->fetch_assoc()){
                        $nombre = $tag['nombres'];
                        $id = $tag['id'];
            ?>  
            <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Nombre:
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    <?php echo $nombre;?>
                                </div>
                            </dd>
                            <button onclick="window.mydialog<?php echo $id;?>.showModal()">
                                <i class="fas fa-edit text-primary-500"></i>
                            </button>
                            <a href="delete_tag.php?del=<?php echo $id; ?>">
                                <i class="fas fa-trash-alt text-red-500"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <dialog id="mydialog<?php echo $id; ?>">
                <p>Agregue el nuevo nombre de la etiqueta</p>
                <form action="tags.php" method="POST">
                    <input type="text" name="name">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="submit" name="update" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                </form>
                <button onclick='window.mydialog<?php echo $id; ?>.close();'>Cerrar modal</button>
            </dialog>
            <?php
                    }
                }else{
                    echo '<div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Nombre:
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    Agrege Una Etiqueta
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>';
                }
            ?>                                        
        </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:bg-gray-700 dark:border-gray-700">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>

                </div>
            </div>
        </div>
    </div>
    <script src="../../js/main.js"></script>
</body>
</html>