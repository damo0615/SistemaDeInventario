<?php
    include '../public/footer.html';
    include '../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';
    $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id LIMIT 10");
    $prod = mysqli_query($conn, "SELECT count(*) AS total FROM producto");
    $pre = mysqli_query($conn, "SELECT SUM(precio) AS total FROM producto");
    $tags = mysqli_query($conn, "SELECT count(*) AS total FROM tag");

    
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
    <?php include '../public/navbarD.php'; ?>

    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                            Dashboard de Inventario
                        </h2>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                <a href="inventario/aggprod.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Agregar Producto
                            </button>
                        </a><?php
                                }
                        ?>
                    </div>
                </div>

                <!-- Estad�sticas -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fas fa-box text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Productos totales
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php 
                                            $total = mysqli_fetch_assoc($prod);
                                            echo $total['total'];       
                                            ?>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fas fa-boxes text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Categorias
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php 
                                            $totalt = mysqli_fetch_assoc($tags);
                                            echo $totalt['total'];       
                                            ?>
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fas fa-box-open text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Bajo stock (< 10)
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            0
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg dark:bg-gray-800">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                        Valor total
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php 
                                            $totalp = mysqli_fetch_assoc($pre);
                                            echo $totalp['total'];       
                                            ?>
                                        </div>
                                    </dd>
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
                            Codigo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Precio
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Categoria
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Proveedor
                        </th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar">
                    <?php
                                if($query->num_rows > 0){
                                    while($prod = $query->fetch_assoc()){
                                            $tag = $prod['nombres'];
                                            $nombre = $prod['nombre'];
                                            $codigo = $prod['codigo'];
                                            $prov = $prod['nombrep'];
                                            $precio = $prod['precio'];
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
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $precio; ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $tag; ?></div>
                                                </td><td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $prov; ?></div>
                                                </td>
                                            </tbody>
                                        <?php
                                    }
                                }
                            ?>
                </tbody>
            </table>
                    </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:bg-gray-700 dark:border-gray-700">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                            </div>
                            <div>
                                <a href="inventario.php">
                                    <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Ver Todos Los Productos
                                </button>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>        
            </div>
        </div>
    </div>
    <script src="../js/main.js"></script> 
    <?php 
    include '../public/footer.html';
?>

</body>
</html>