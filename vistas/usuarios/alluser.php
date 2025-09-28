<?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    include '..\php_inyec.php';
    session_start();
    $user_id = $_SESSION['id'];
    $rol = $_SESSION['rol'];
    $vista=0;
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    if ($rol >= 3) {
        header("location:viewuser.php");
    }
    $query = mysqli_query($conn, "SELECT usuario.id,nombres,nombre,username,email,estatus FROM usuario INNER JOIN permisos ON usuario.id_permiso = permisos.id");
    if (isset($_POST['buscar'])) {
        $busqueda = limpiar_cadena($_POST['texto']);
        $query = mysqli_query($conn, "SELECT usuario.id,nombres,nombre,username,email,estatus FROM usuario INNER JOIN permisos ON usuario.id_permiso = permisos.id WHERE nombres='$busqueda'OR dni='$busqueda' OR email='$busqueda' OR username='$busqueda'");
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
    <link rel="stylesheet" type="text/css" href="../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../../public/navbar.php'; ?>
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
                        <button type="button" onclick="window.mydialog.showModal()" id="add-product-btn" class="ml-3 float-right inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-x mr-2"></i> Cerrar Sesion
                        </button>
                        <div class="mt-4 flex md:mt-0 md:ml-4 float-right">
                        <a href="agregar_usuario.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Agregar Usuario <i class="fa-solid fa-user-plus"> </i>
                            </button>
                        </a>
                    </div>
                    </div>
                </div>
                <table>
                    <thead>
                        <th><h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Buscar por:</h3></th>
                    </thead>
                    <tbody>
                        <form method="POST">
                            <td>
                                <input type="text" name="texto" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" data-validate="no-especiales">
                            </td>
                            <td>
                                <input type="submit" name="buscar" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 float-right">
                            </td>  
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Tabla de productos -->
                <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Todos los usuarios que hacen vida en el sistema
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            verifica el estado de cada uno y gestiona sus acciones
                        </p>
                        <?php if (isset($_SESSION['mensaje_exito'])): ?>
                            <div class="message success">
                                <?php echo htmlspecialchars($_SESSION['mensaje_exito']); ?>
                                <span class="close-btn" data-form="limpiar_exito">&times;</span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['mensaje_error'])): ?>
                            <div class="message error">
                                <?php echo htmlspecialchars($_SESSION['mensaje_error']); ?>
                                <span class="close-btn" data-form="limpiar_error">&times;</span>
                            </div>
                        <?php endif; ?>
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
    <dialog id="mydialog" class="pop">
        <div class="popitems">
            <h1>¿Seguro que quiere cerrar sesion?</h1>
            <br>
            <button onclick='window.location.href = "../../sesion/logout.php";' class="rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:text-sm">Si, cerrar</button>
            <button onclick='window.mydialog.close();' class="rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:text-sm">Cancel</button>
            </div>
    </dialog>
    <script src="../../js/main.js"></script>
    <script src="../../js/validador.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    <?php 
                        unset($_SESSION['mensaje_exito']);
                        unset($_SESSION['mensaje_error']);
                    ?>
                });
            }
        });
    </script>
</body>
</html>