 <?php
    include '../../sesion_time.php';
    include '../../db/db.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $query = mysqli_query($conn, "SELECT * FROM proveedor");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"  type="text/css" href="../../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegacion -->
    <?php include '../../public/navbar.php'; ?>
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                <a href="aggprov.php">
                                    <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i> Agregar Proveedor
                                    </button>
                                </a><?php
                                }
                        ?>
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
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        
                        <a href="../inventario.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Productos
                            </button>
                        </a>
                        <a href="tags.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Etiquetas
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de productos -->
    <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Listado Total de Proveedores
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Codigo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Direccion
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Observaciones
                        </th>
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Acciones
                        </th><?php
                                }
                        ?>
                    </tr>
                </thead>
                <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
                    <?php
                                if($query->num_rows > 0){
                                    while($prove = $query->fetch_assoc()){
                                            $nombre = $prove['nombrep'];
                                            $codigo = $prove['codigop'];
                                            $direccion = $prove['direccion'];
                                            $observacion = $prove['observacion'];
                                        ?>  
                                            
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
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $direccion; ?></div>
                                                </td>
                                                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider dark:text-gray-300">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $observacion; ?></div>
                                                </td>
                                                <?php
                                                    if($_SESSION['rol'] != 3){ ?>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <a href="editar_prove.php?edit=<?php echo $prove['id']?>" class="edit-product text-primary-500 hover:text-primary-600 mr-3" data-id="${product.id}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button class="text-red-500 hover:text-red-600 mr-3" onclick="window.mydialog<?php echo $prove['id']; ?>.showModal();">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                            <dialog id="mydialog<?php echo $prove['id']; ?>">
                                                                <p>Introduzca la contraseña para eliminar el item</p>
                                                                <form action="delete_provee.php" method="POST">
                                                                    <input type="password" name="clave">
                                                                    <input type="hidden" name="id" value="<?php echo $prove['id']?>">
                                                                    <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                                                                </form>
                                                                <button onclick='window.mydialog<?php echo $prove['id']; ?>.close();'>Cerrar modal</button>
                                                            </dialog>
                                                </td><?php
                                                    }
                                                ?>

                                                
                                            </tbody>
                                        <?php
                                    }
                                }
                            ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <script src="../../js/main.js"></script>
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
<?php include '../../public/footer.html'; ?>
</html>