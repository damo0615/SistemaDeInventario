<?php
    include '../sesion_time.php';
    session_start();
    $user_id = $_SESSION['id'];
    $username = $_SESSION['usern'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';
    $prove = mysqli_query($conn, "SELECT id,nombrep FROM proveedor");
    $tag = mysqli_query($conn, "SELECT id,nombres FROM tag");
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
    if (isset($_POST['limpiar_mensaje'])) {
        unset($_SESSION['mensaje_exito']);
        unset($_SESSION['mensaje_error']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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
    <!-- Contenido principal -->
    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <?php
                            if($_SESSION['rol'] != 3){ ?>
                                    <button onclick="window.mydialogAgregarP.showModal()" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i> Agregar Producto
                                    </button>
                                <?php
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
    <button onclick="toggleseccion()">Mostrar Ocultar Tabla</button>
    <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800 mostrar" id="product-list">
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
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Ultimos productos agregados
                </p>
                <table id="buscar">
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
        <!-- Formulario de editar Productos -->
        <dialog id="mydialog<?php echo $id; ?>">
            <p>Introduzca la contraseña para editar el item</p>
            <form action="inventario/editar_prod.php" method="POST">
                <input type="password" name="clave">
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <input type="submit" name="editar" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
            </form>
            <button onclick='window.mydialog<?php echo $id; ?>.close();' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar modal</button>
        </dialog>  
        <!-- Modal para eliminar Productos -->                                              
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
    <!-- Modal para agregar productos -->
        <dialog id="mydialogAgregarP" class="min-h-screen pt-4 px-4 pb-20">
            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Agregar Nuevo Producto
                                </h3>
                            </div>
                            <div class="mt-2">
                                <form action="aggprod.php" method="POST">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6">
                                            <label for="product-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto</label>
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
                                            <textarea id="product-descrip" name="product-descrip" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Maximo 255 caracteres" required></textarea>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                                        <input type="submit" name="send" id="save-product" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Producto">
                                        <button onclick='window.mydialogAgregarP.close();' type="button" id="cancel-add" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">Cancelar </button>
                                    </div>
                                </form>
                            </div>
            
        </dialog>
</div>
    <script src="../js/main.js">
        function toggleseccion(){
            const tabla_producto_vista = document.getElementByClass('mostrar');
            const tabla_producto_oculta = document.getElementByClass('ocultar');

            if (tabla_producto_vista) {
                tabla_producto_vista.classList.add = 'ocultar';   
                tabla_producto_vista.classList.remove = 'mostrar';   
            }
        }

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
<?php include '../public/footer.html'; ?>
</html>