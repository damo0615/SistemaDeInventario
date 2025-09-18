<?php
    include '../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    $username = $_SESSION['usern'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';
    $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id");
    include '../public/footer.html';
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
    <link rel="stylesheet" href="../style.css">
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
                            Caja
                        </a>
                        <a href="inventario.php" class="border-primary-500 text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold"><?php echo $username; ?></div>
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
                <a href="inventario.php" class="bg-primary-500 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:bg-gray-700 dark:text-primary-400">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
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
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <a href="inventario/proveedores.php">
                            <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i> Ver Proveedores
                            </button>
                        </a>
                        <a href="inventario/tags.php">
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
            <div class="flex-1 min-w-0">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Inventario de Productos
                    <a href="inventario/stock.php">
                        <button type="button" id="add-product-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 float-right">
                            <i class="fas fa-plus mr-2"></i> Gestion de Stock
                        </button>
                    </a>
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Ultimos productos agregados
                </p>
            </div>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Stock
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
                                    while($prod = $query->fetch_assoc()){
                                            $tag = $prod['nombres'];
                                            $nombre = $prod['nombre'];
                                            $codigo = $prod['codigo'];
                                            $prov = $prod['nombrep'];
                                            $precio = $prod['precio'];
                                            $stock = $prod['cantidad'];
                                            $id = $prod['cantidad'];
                                            $descrip = $prod['descripcion'];
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
                                                </td><td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white"><?php echo $stock; ?></div>
                                                </td>
                                                </td>
                                                <?php
                                                    if($_SESSION['rol'] != 3){ ?>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button onclick="window.mydialog0<?php echo $id;?>.showModal()">
                                                        <i class="fa-solid fa-eye text-primary-500 hover:text-primary-600 mr-3"></i>
                                                    </button>
                                                    
                                                    <button onclick="window.mydialog<?php echo $id;?>.showModal()">
                                                        <i class="fas fa-edit text-primary-500 hover:text-primary-600 mr-3"></i>
                                                    </button>
                                                    <button class="delete-product text-red-500 hover:text-red-600" data-id="${product.id}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
    <dialog id="mydialog0<?php echo $id; ?>" class="seedialog">
        <p>Introduzca la contraseña para eliminar el item</p>
        <div class="mt-2">
                    <form action="aggprod.php" method="POST">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="product-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto</label>
                                <h3></h3>
                                <input type="text" maxlength="25" name="product-name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                <select id="product-category" name="product-category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                    <?php
                                        if($tag->num_rows > 0){
                                            while($tags = $tag->fetch_assoc()){
                                                $ntag = $tags['nombres'];
                                                $itag = $tags['id'];
                                    ?>
                                    <option value="<?php echo $itag;?>"><?php  echo $ntag; ?></option>
                                    <?php  }} ?>
                                </select>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                                <input type="text" maxlength="25" name="product-codigo" id="product-codigo" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="text" maxlength="25" name="product-price" id="product-price" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="0.00" required>
                                    
                                </div>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-prov" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proveedor</label>
                                <select id="product-prov" name="product-prov" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                    <?php
                                        if($prove->num_rows > 0){
                                            while($provee = $prove->fetch_assoc()){
                                                $nprove = $provee['nombrep'];
                                                $iprove = $provee['id'];
                                    ?>
                                    <option value="<?php echo $iprove;?>"><?php  echo $nprove; ?></option>
                                    <?php  }} ?>
                                </select>
                            </div>
                            
                            <div class="col-span-6">
                                <label for="product-descrip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripcion</label>
                                <textarea id="product-descrip" maxlength="25" name="product-descrip" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Maximo 255 caracteres" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                    <input type="submit" name="send" id="save-product" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Producto">
                    <a href="../inventario.php">
                        <button type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">                        Cancelar
                    </button>
                    </a>
                </div>
                    </form>
                </div>
        <button onclick='window.mydialog0<?php echo $id; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
    </dialog>
    <dialog id="mydialog<?php echo $id; ?>">
        <p>Introduzca la contraseña para eliminar el item</p>
        <form action="delete_tag.php" method="POST">
            <input type="password" name="clave">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
        </form>
        <button onclick='window.mydialog<?php echo $id; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
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

        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:bg-gray-700 dark:border-gray-700">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/main.js"></script>
</body>
</html>