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
    /*BUSQUEDA DE PRODUCTOS*/
    if (isset($_POST['buscar'])) {
        $campo = $_POST['campo'];
        $busqueda = $_POST['texto'];
        if ($campo == 'nombre') {
            $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id WHERE nombre = '$busqueda'");
        }if ($campo == 'codigo') {
            $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id WHERE codigo = '$busqueda' ");
        }if ($campo == 'categoria') {
            $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id WHERE nombres = '$busqueda' ");
        }
        if ($campo == 'Proveedor') {
            $query = mysqli_query($conn, "SELECT * FROM producto INNER JOIN tag ON producto.id_tag = tag.id INNER JOIN proveedor ON producto.id_proveedor = proveedor.id INNER JOIN inventario ON inventario.id_producto = producto.id WHERE nombrep = '$busqueda' ");
        }
    }
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
    <?php include '../public/navbar.php'; ?>
    <?php include '../public/navbar.php'; ?>
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
                <table>
                    <thead>
                        <th><h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Buscar por:</h3></th>
                    </thead>
                    <tbody>
                        <form method="POST">
                        <td>
                            
                                <select name="campo">
                                    <option value="nombre">Nombre</option>
                                    <option value="codigo">Codigo</option>
                                    <option value="categoria">Categoria</option>
                                    <option value="Proveedor">Proveedor</option>
                                </select>
                        </td>
                        <td>
                            <input type="text" name="texto">
                        </td>
                        <td>
                            <input type="submit" name="buscar">
                        </td>
                        </form>
                    </tbody>
                </table>
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
                                            $id = $prod['id'];
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
                                                </td><td class="px-6 py-4 whitespace-nowrap bg-<?php if ($stock <= 0) {
                                                    echo 'red-300';}
                                                    else if ($stock <= 5) {
                                                        echo 'orange-300';

                                                }?>">
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
                                                    <button onclick="window.mydialogDelete<?php echo $id;?>.showModal()" class="delete-product text-red-500 hover:text-red-600" data-id="${product.id}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
<dialog id="mydialog0<?php echo $id; ?>" class="pop">
    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Detalles Del Producto:</h3><button onclick='window.mydialog0<?php echo $id; ?>.close();' class="w-full inline-flex float-right justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
        <h2>Codigo: <?php echo $codigo; ?></h2>
        <h2>Nomre: <?php echo $nombre; ?></h2>
        <h2>Precio: <?php echo $precio; ?></h2>
        <h2>Etiqueta: <?php echo $tag; ?></h2>
        <h2>Proveedor: <?php echo $prov; ?></h2>
        <h2>Cantidad en Stock: <?php echo $stock; ?></h2>
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
<dialog id="mydialogDelete<?php echo $id; ?>" class="po+p">
    <p>Introduzca la contraseña para eliminar el item</p>
    <form action="inventario/delete_prod.php" method="POST">
        <input type="password" name="clave">
        <input type="hidden" name="id" value="<?php echo $id;?>">
        <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
    </form>
    <button onclick='window.mydialogDelete<?php echo $id; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
</dialog>
    <?php
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