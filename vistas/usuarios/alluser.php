<?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $query = mysqli_query($conn, "SELECT usuario.id,nombres,nombre,username,email,estatus FROM usuario INNER JOIN permisos ON usuario.id_permiso = permisos.id");
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
    <link rel="stylesheet" type="text/css" href="../../style.css">
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
                        <a href="../dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <a href="../inventario.php" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
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
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Todos los Usuarios
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de productos -->
                <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Inventario de Productos
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Ultimos productos agregados
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Rol
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Usuario
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <?php
                                if($query->num_rows > 0){
                                    while($user = $query->fetch_assoc()){
                                            $nombre = $user['nombres'];
                                            $rol = $user['nombre'];
                                            $username = $user['username'];
                                            $email = $user['email'];
                                            $status = $user['estatus'];
                                            $id = $user['id'];
                                            if($status == 0){
                                                $iconStatus = 'fas fa-times-circle text-red-500 dark:text-red-300';
                                                $nameStatus = 'Bloqueado';
                                            }else{
                                                $iconStatus = 'fas fa-check-circle text-green-500 dark:text-green-300';
                                                $nameStatus = 'Activo';
                                            }
                                        ?>  
                                            
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center dark:bg-primary-200">
                                                            <i class="fas fa-box text-primary-600"></i>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo $nombre; ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $rol; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $username; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $email; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClasses}">
                                                        <i class="<?php echo $iconStatus; ?>"></i><?php echo $nameStatus; ?> 
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <?php 
                                                    if ($id != $user_id) {
                                                        ?><a href="editar_status.php?edit=<?php echo $user['id']?>&status=<?php echo $status;?>" class="edit-product text-primary-500 hover:text-primary-600 mr-3" data-id="${product.id}">
                                                        <?php
                                                            if($status == 0){
                                                                echo '<button class="edit-product text-green-500 hover:text-green-600 mr-3" data-id="${product.id}">
                                                            <i class="fa-solid fa-lock-open"></i>
                                                            </button>';
                                                            }else{
                                                               echo '<button class="edit-product text-red-500 hover:text-red-600 mr-3" data-id="${product.id}">
                                                            <i class="fa-solid fa-lock"></i>
                                                            </button>'; 
                                                            }
                                                        ?>
                                                    </a><?php
                                                    }                                                    
                                                    ?>
                                                    <a href="editar_user_admin.php?edit=<?php echo $user['id']?>" class="edit-product text-primary-500 hover:text-primary-600 mr-3" data-id="${product.id}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="window.mydialog<?php echo $user['id'];?>.showModal()">
                                                        <i class="fas fa-trash-alt text-red-500"></i>
                                                    </button>
                                                    <dialog id="mydialog<?php echo $user['id']; ?>">
                                    <p>Introduzca la contraseña para eliminar el item</p>
                                    <form action="delete_user.php" method="POST">
                                        <input type="password" name="clave">
                                        <input type="hidden" name="id" value="<?php echo $user['id']?>">
                                        <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                                    </form>
                                    <button onclick='window.mydialog<?php echo $user['id']; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
                                </dialog>
                                                </td>
                                            </tbody>
                                        <?php
                                    }
                                }
                            ?>
                            
                        </table>
                    </div>
                </div>
    <script src="../../js/main.js"></script>
</body>
</html>