<?php
    include '../sesion_time.php';
    include '../vistas/php_inyec.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';
    $query = mysqli_query($conn, "SELECT id,nombrec, codigo, dni, telefono FROM clientes");
    
    if (!empty($_POST['update'])) {
        $nombre = limpiar_cadena($_POST['nombre']);
        $codigo = limpiar_cadena($_POST['codigo']);
        $dni = limpiar_cadena($_POST['dni']);
        $telefono = limpiar_cadena($_POST['telefono']);
        $id = limpiar_cadena($_POST['cliente']);
        $query = mysqli_query($conn, "SELECT codigo,dni FROM clientes WHERE codigo='$codigo' OR dni='$dni'");        
        if ($query->num_rows > 1) {
            $_SESSION['mensaje_error'] = "El codigo o DNI coinciden con otro cliente, por favor intente con otro";
        }else{
            $query = mysqli_query($conn, "UPDATE clientes SET nombrec='$nombre',codigo='$codigo',dni='$dni',telefono='$telefono' WHERE id='$id'");
            $id_user = $_SESSION['id'];
            $accion = 'El usuario '.$_SESSION['usern'].' ha actualizado un cliente';
            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
            if(!$bitacora){
                die("Query Failed");
            }
            $query = mysqli_query($conn, "SELECT id,nombrec, codigo, dni, telefono FROM clientes");
            $_SESSION['mensaje_exito'] = 'cliente editado con exito';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../public/navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">

                <!-- Tabla de productos -->
                <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Listado de clientes
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Movimientos y gestion de clientes
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
                                        Codigo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        DNI
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Telefonos
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($query->num_rows > 0){
                                        while($cliente = $query->fetch_assoc()){
                                            $nombre = $cliente['nombrec'];
                                            $dni = $cliente['dni'];
                                            $telefono = $cliente['telefono'];
                                            $codigo = $cliente['codigo'];
                                        ?>  
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center dark:bg-primary-200">
                                                                <i class="fas fa-box text-primary-600"></i>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo $codigo; ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white"><?php echo $nombre; ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white"><?php echo $dni; ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white"><?php echo $telefono; ?></div>
                                                    </td>
            <?php
            if($_SESSION['rol'] != 3){ ?>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="submit" onclick="window.editar<?php echo $cliente['id']; ?>.showModal();" class="edit-product text-primary-500 hover:text-primary-600 mr-3" data-id="${product.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-500 hover:text-red-600 mr-3" onclick="window.mydialog<?php echo $cliente['id']; ?>.showModal();">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <dialog id="mydialog<?php echo $cliente['id']; ?>">
                        <p>Introduzca la contraseña para eliminar el item</p>
                        <form action="usuarios/delete_cliente.php" method="POST">
                            <input type="password" name="clave">
                            <input type="hidden" name="id" value="<?php echo $cliente['id']?>">
                            <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                        </form>
                        <button onclick='window.mydialog<?php echo $cliente['id']; ?>.close();'>Cerrar modal</button>
                    </dialog>
                </td>
                <?php
            }
        ?>
                                                </tr>
                    <dialog id="editar<?php echo $cliente['id']; ?>" class="pop">
                        <form method="POST" action="clientes.php">
                            <div class="grid grid-cols-6 gap-8">
                                <div class="col-span-6">
                                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                                        Editar Datos del Cliente:
                                    </h2>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                    <input type="text" maxlength="25" name="nombre" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required value="<?php echo $nombre; ?>" data-validate="no-especiales">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                                    <input type="text" maxlength="15" name="dni" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="numero" value="<?php echo $dni; ?>">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                                    <input type="text" maxlength="25" name="codigo" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="no-especiales" value="<?php echo $codigo; ?>">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                                    <input type="text" maxlength="15" name="telefono" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required data-validate="numero" value="<?php echo $telefono; ?>">
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <input type="submit" id="save-product" name="update" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Actualizar Cliente"></input>
                                    <input type="hidden" name="cliente" value="<?php echo $cliente['id']; ?>">
                                </div>
                                <button onclick='window.editar<?php echo $cliente['id']; ?>.close();' type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">Cancelar </button>
                            </div>
                        </form>
                    </dialog>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>        
            </div>
        </div>
    </div>
    <script src="../js/main.js"></script> 
    <script src="../../js/validador.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    <?php 
                        unset($_SESSION['mensaje_exito']);
                        unset($_SESSION['mensaje_error']);
                        unset($_SESSION['mensaje_sql']);
                    ?>
                });
            }
        });
    </script>
</body>
</html>