<?php
    include '../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    if($_SESSION['rol'] == 3){
        header("location:usuarios/viewuser.php");
    }
    include '../db/db.php';
    $users = mysqli_query($conn, "SELECT COUNT(*) AS total FROM usuario ");
    $activo = mysqli_query($conn, "SELECT COUNT(*) AS total FROM usuario WHERE estatus=1");
    $bloqueado = mysqli_query($conn, "SELECT COUNT(*) AS total FROM usuario WHERE estatus=0");
    $username = mysqli_query($conn, "SELECT username FROM usuario WHERE id='$user_id'");
    if(!$users){
        die("Query Failed");
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
                        <a href="dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="caja.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            caja
                        </a>
                        <a href="inventario.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Inventario
                        </a>
                        <a href="reporte.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                            <a href="usuario.php">
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
                <a href="dashboard.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Dashboard
                </a>
                <a href="caja.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Caja
                </a>
                <a href="dashboard.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Inventario
                </a>
                <a href="reporte.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Reportes
                </a>
            </div>
        </div>
    </nav>
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-5">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Gestion de Usuario
                        </h2>
                        <h4 class="text-1xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Panel de Administrador
                        </h4>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <a href="usuarios/agguser.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Agregar Usuario <i class="fa-solid fa-user-plus"> </i>
                            </button>
                        </a>
                    </div>
                </div>
                <!-- Estad�sticas -->
                <div class="grid grid-cols-1 mb-8 gap-1 sm:grid-cols-1 lg:grid-cols-3">
                    <a href="usuarios/viewuser.php">
                        <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800 hover:bg-primary-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fa-solid fa-users text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Ver Usuario
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php
                                                $name = mysqli_fetch_assoc($username);
                                                echo $name['username'];
                                            ?>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                    <a href="usuarios/alluser.php">
                        <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                        <div class="px-4 py-5 sm:p-6 hover:bg-primary-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fas fa-boxes text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Todos Los Usuarios
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php
                                                $total = mysqli_fetch_assoc($users);
                                                echo $total['total'];
                                            ?>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                    <a href="#">
                        <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800 hover:bg-primary-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fa-solid fa-users-slash text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Usuarios Activos
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php
                                                $act = mysqli_fetch_assoc($activo);
                                                echo $act['total'];
                                            ?>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                    <a href="#">
                        <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800 hover:bg-primary-200">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                        <i class="fa-solid fa-users-gear text-white text-xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                            Usuarios Bloqueados
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                <?php
                                                $block = mysqli_fetch_assoc($bloqueado);
                                                echo $block['total'];
                                            ?>
                                            </div>
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <a href="../sesion/logout.php">
            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-x mr-2"></i> Cerrar Sesion
            </button>
        </a>
    </div>
    <!-- Footer 
    <footer class="bg-white rounded-lg shadow-sm dark:bg-gray-900 m-4">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <i class="fas fa-boxes text-primary-500 text-2xl mr-2"></i>
                <span class="text-xl font-bold text-gray-900 dark:text-white">Inventory Pro</span>
                <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">About</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">Contact</a>
                    </li>
                </ul>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
        </div>
    </footer> --> 
    <script src="../js/main.js"></script>
</body>
</html>