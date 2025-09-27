<?php
    include '../sesion_time.php';
    include '../vistas/php_inyec.php';
    session_start();
    $user_id = $_SESSION['id'];
    $user_n = $_SESSION['usern'];
    if(!isset($user_id)) {
        header("location:../sesion/login.php");
    }
    include '../db/db.php';
    $cliente = "";
    if (!empty($_POST['buscar'])) {
        $busqueda = $_POST['busqueda'];
        $campo = $_POST['campo'];
        if ($campo == 'nombre') {
            $query = mysqli_query($conn, "SELECT * FROM clientes WHERE nombrec = '$busqueda'");
            $cliente = "buscar";
        }else if ($campo == 'codigo') {
            $query = mysqli_query($conn, "SELECT * FROM clientes WHERE codigo = '$busqueda'");
            $cliente = "buscar";
        }
    }
    if (!empty($_POST['confirmar'])){
        $codigo = $_POST['codigo'];
        $query_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE codigo = '$codigo'");
        if(!$query_cliente){
            die("Query Failed");
        }
        $cliente = "confirmado";
    }
    if (!empty($_POST['send'])) {
        $nombre = $_POST['name'];
        $codigo = $_POST['codigo'];
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        $query = mysqli_query($conn, "INSERT INTO clientes (nombrec,dni,codigo,telefono) VALUES ('$nombre','$dni','$codigo','$telefono')");
        if(!$query){
            die("Query Failed");
        }
        $cliente = "confirmado";
        $query_cliente = mysqli_query($conn,"SELECT * FROM clientes WHERE codigo = '$codigo'");
        if(!$query_cliente){
            die("Query Failed");
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['procesar_movimiento'])) {
        $lista_productos = json_decode($_POST['lista_productos'], true);
        $cliente_id = $_POST['cliente'];

        // Iniciar la transacción para asegurar la consistencia de los datos
        mysqli_begin_transaction($conn);
        $todo_ok = true;
        $mensaje_error = '';

        if (!is_array($lista_productos) || empty($lista_productos)) {
            $todo_ok = false;
            $mensaje_error = "No hay productos en la lista para procesar.";
        }
        $sub_total = 0;
        foreach ($lista_productos as $producto) {
            $cantidad = (int)$producto['cantidad'];
            $precio = $producto['precio'];
            $pro_total = $cantidad * $precio;
            $sub_total += $pro_total; 
        }
        if ($todo_ok) {
            //Guardar el movimiento en la tabla compra
            $sql_compra = "INSERT INTO compra (id_user, id_cliente, fecha, total) VALUES (?, ?, NOW(), ?)";
            $stmt_compra = mysqli_prepare($conn, $sql_compra);
            mysqli_stmt_bind_param($stmt_compra, "iid", $user_id, $cliente_id, $sub_total);
            if (!mysqli_stmt_execute($stmt_compra)) {
                throw new Exception("Error al registrar el movimiento");
            }else{
                $id_venta = $conn->insert_id;
                    foreach ($lista_productos as $producto) {
                        $id_producto = $producto['id'];
                        $cantidad = (int)$producto['cantidad'];
                        $precio = $producto['precio'];

                        if ($cantidad <= 0) {
                            $_SESSION['mensaje_error'] = "Error al registrar el movimiento, verifique que cuenta con el stock necesario de cada producto";
                        break;
                        }
                        if ($todo_ok) {
                            //Guardar el movimiento en la tabla compra
                            $sql_compra_det = "INSERT INTO detalles_compra (id_compra, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
                            $stmt_compra_det = mysqli_prepare($conn, $sql_compra_det);
                            mysqli_stmt_bind_param($stmt_compra_det, "iiid", $id_venta, $id_producto, $cantidad, $precio);
                            if (!mysqli_stmt_execute($stmt_compra_det)) {
                                $_SESSION['mensaje_error'] = "Error al registrar el movimiento";
                                throw new Exception("Error al registrar el movimiento");
                            }
                        }

                        try {

                            // Obtener el ID del inventario y la cantidad actual del producto
                            $sql_inventario = "SELECT id, cantidad FROM inventario WHERE id_producto = ?";
                            $stmt_inventario = mysqli_prepare($conn, $sql_inventario);
                            mysqli_stmt_bind_param($stmt_inventario, "i", $id_producto);
                            mysqli_stmt_execute($stmt_inventario);
                            $result = mysqli_stmt_get_result($stmt_inventario);
                            
                            if (mysqli_num_rows($result) === 0) {
                                $_SESSION['mensaje_error'] = "No se encontro el podructo en el inventario";
                                throw new Exception("Producto con ID '" . $id_producto . "' no encontrado en el inventario.");
                            }
                            $row = mysqli_fetch_assoc($result);
                            $id_inventario = $row['id'];
                            $cantidad_actual = $row['cantidad'];
                    
                            // Calcular la nueva cantidad
                            $nueva_cantidad = $cantidad_actual;
                            if ($cantidad_actual < $cantidad) {
                                $_SESSION['mensaje_error'] = "Error al registrar el movimiento, no hay stock suficiente para proceder";
                                throw new Exception("Stock insuficiente para " . $producto['nombre'] . ". Disponible: $cantidad_actual");
                            }
                            $nueva_cantidad -= $cantidad;
                            
                            
                            // Actualizar la cantidad en la tabla 'Inventario'
                            $sql_update = "UPDATE inventario SET cantidad = ? WHERE id = ?";
                            $stmt_update = mysqli_prepare($conn, $sql_update);
                            mysqli_stmt_bind_param($stmt_update, "ii", $nueva_cantidad, $id_inventario);
                            if (!mysqli_stmt_execute($stmt_update)) {
                                $_SESSION['mensaje_error'] = "Error al actualizar el inventario";
                                throw new Exception("Error al actualizar el inventario para el producto " . $producto['nombre'] . ".");
                            }

                            // Registrar el movimiento en la tabla 'movimiento_inventario'
                            $sql_movimiento = "INSERT INTO movimientos_inventario (id_inv, tipo,cantidad, fecha, cantidad_actual) VALUES (?, 'V', ?, NOW(), ?)";
                            $stmt_movimiento = mysqli_prepare($conn, $sql_movimiento);
                            mysqli_stmt_bind_param($stmt_movimiento, "iii", $id_inventario, $cantidad, $nueva_cantidad);
                            if (!mysqli_stmt_execute($stmt_movimiento)) {
                                $_SESSION['mensaje_error'] = "Error al registrar el movimiento para el producto". $producto['nombre'];
                                throw new Exception("Error al registrar el movimiento para el producto " . $producto['nombre'] . ".");
                            }

                        } catch (Exception $e) {
                            $todo_ok = false;
                            $mensaje_error = $e->getMessage();
                            break; // Detener el bucle si hay un error
                        }
                    } // Fin del bucle foreach
                    if ($todo_ok) {
                    mysqli_commit($conn);
                    $_SESSION['mensaje_exito'] = "La operacion fue realizada con exito"; 
                     $mensaje_exito = "Todas las operaciones de pedido realizadas con éxito.";
                    } else {
                         mysqli_rollback($conn);
                    } 

                }
            }
        }    

    // Obtener la lista de productos para el autocompletado y búsqueda
    $productos_json = "[]";
    $query_productos = mysqli_query($conn, "SELECT id, nombre, codigo, precio FROM producto");
    if (mysqli_num_rows($query_productos) > 0) {
        $productos_data = [];
        while ($row = mysqli_fetch_assoc($query_productos)) {
            $productos_data[] = $row;
        }
        $productos_json = json_encode($productos_data);
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
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Barra de navegaci�n -->
    <?php include '../public/navbar.php'; ?>
    <?php 
        if ($cliente == "") {
            echo '<canvas class="bg-gray-200 min-w-full"></canvas>';
        }elseif ($cliente == "buscar") {
            echo '<canvas class="bg-gray-200 min-w-full"></canvas>';
        }else{ ?>
    <!-- Contenido principal -->
    <div class="pt-4 pb-4x">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Header -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                            Movimientos
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabla de productos -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6" >
        <div class="bg-white shadow overflow-hidden rounded-lg dark:bg-gray-800">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    INVERSIONES MERCAMIX MV
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Usuario: <?php echo $user_n; ?></p>
                <?php if ($cliente == "confirmado") {
                        if ($query_cliente -> num_rows > 0) {
                            while($cli = $query_cliente->fetch_assoc()){
                                $nombre = $cli['nombrec'];
                                $id = $cli['id'];
                                 ?>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Cliente: <?php echo $nombre; ?></p>
                                <form id="add-form">
                                    <div class="grid grid-cols-6 gap-8">
                                        <div class="col-span-6 sm:col-span-3">
                                            <input type="text" maxlength="25" id="codigo_producto" placeholder="Código de Producto" list="codigos" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                        </div>
                                        <div class="col-span-6 sm:col-span-1">
                                            <input type="number" min="1" max="200" maxlength="3" id="cantidad_producto" placeholder="Cantidad" min="1" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                        </div>
                                        <div class="col-span-6 sm:col-span-1">
                                            <button type="submit" id="add-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Agregar</button>
                                            <datalist id="codigos">
                                            </datalist>
                                        </div>
                                    </div>
                                </form>
                                <form id="main-form" method="POST">
                                    <input type="hidden" name="cliente" value="<?php echo $id; ?>">
                                    <div class="lista-productos bg-white shadow shadow-primary-200 overflow-hidden rounded-lg dark:bg-gray-800">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                        nombre
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                        codigo
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                        precio
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                        cantidad
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                        total
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 custom-scrollbar" id="product-list">
                                                
                                            </tbody>
                                        </table>
                                        <ul  class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        </ul>
                                    </div>
                                    
                                    <input type="hidden" name="lista_productos" id="lista_productos">
                                    <button type="submit" name="procesar_movimiento" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" >Procesar Movimiento</button>
                                </form>
                                
                                <?php
                            }
                        } 
                    ?>
            </div>
            <div class="overflow-x-auto">
            </div>
        </div>
    </div>
      <?php  }
    ?>
    <!-- Dialog de clientes -->
    <?php }if ($cliente == "") { ?>
        <form method="POST">
            <div class="grid grid-cols-6 gap-8 super">
                <div class="col-span-6">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                        Busque un cliente para proceder:
                    </h2>
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
                <div class="col-span-6 sm:col-span-3">
                    <label for="user-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar Por:</label>
                    <select id="product-category" name="campo" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        <option value="nombre">Nombre</option>
                        <option value="codigo">Codigo</option>
                    </select>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="user-name" class="block text-sm font-medium text-white-700 dark:text-white-300">.</label>
                    <input type="text" maxlength="25" name="busqueda" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <input type="submit" id="save-product" name="buscar" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Buscar Usuario"></input>
                </div>
            </div>
        </form>
    <?php 
        }elseif($cliente == "buscar"){ 
            if ($query -> num_rows > 0) {
                while($cli = $query->fetch_assoc()){ 
                    $nombre = $cli['nombrec'];
                    $dni = $cli['dni'];
                    $codigo = $cli['codigo'];
                    $telefono = $cli['telefono'];
                    ?>
                    <form action="caja.php" method="POST">
                        <div class="grid grid-cols-6 gap-8 super">
                            <div class="col-span-6">
                                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                                    Confirmar
                                </h2>
                            </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Cliente</label>
                                    <input type="text" maxlength="25" name="name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" value="<?php echo $nombre;?>" required >
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                                    <input type="text" maxlength="25" name="dni" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" value="<?php echo $dni;?>" required >
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                                    <input type="text" maxlength="25" name="codigo" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" value="<?php echo $codigo;?>" required >
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                                    <input type="text" maxlength="25" name="telefono" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" value="<?php echo $telefono;?>" required >
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <input type="submit" id="save-product" name="confirmar" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="confirmar"></input>
                                </div>
                            </div>
                    </form>
                    <?php
                }
            }else{ ?>
                <form method="POST">
                    <div class="grid grid-cols-6 gap-8 super">
                        <div class="col-span-6">
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                                No existe un cliente con los datos que buscas, puedes registrarlo:
                            </h2>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                            <input type="text" maxlength="25" name="name" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required value="<?php echo $busqueda;?>">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                            <input type="text" maxlength="25" name="dni" id="product-name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required >
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codigo</label>
                            <input type="text" maxlength="25" name="codigo" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required >
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                            <input type="text" maxlength="25" name="telefono" id="product-stock" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required >
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <input type="submit" id="save-product" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Guardar Usuario"></input>
                        </div>
                    </div>
                </form>
           <?php }
        }
        ?>
    <script src="../js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productosData = <?php echo $productos_json; ?>;
            const datalist = document.getElementById('codigos');
            const listaProductosUl = document.getElementById('product-list');
            const addForm = document.getElementById('add-form');
            const codigoInput = document.getElementById('codigo_producto');
            const cantidadInput = document.getElementById('cantidad_producto');
            const mainForm = document.getElementById('main-form');
            const listaProductosInput = document.getElementById('lista_productos');
            const closeBtn = document.querySelector('.close-btn-mensaje');
            
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    <?php 
                        unset($_SESSION['mensaje_exito']);
                        unset($_SESSION['mensaje_error']);
                    ?>
                });
            }
            let productosEnLista = [];

            // Llenar el datalist
            productosData.forEach(p => {
                const option = document.createElement('option');
                option.value = p.codigo;
                datalist.appendChild(option);
            });

            addForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const codigo = codigoInput.value;
                const cantidad = parseInt(cantidadInput.value);

                if (isNaN(cantidad) || cantidad <= 0) {
                    alert('La cantidad debe ser un número positivo.');
                    return;
                }

                const productoEncontrado = productosData.find(p => p.codigo === codigo);

                if (productoEncontrado) {
                    const yaExiste = productosEnLista.find(p => p.id === productoEncontrado.id);
                    if (yaExiste) {
                        alert('Este producto ya está en la lista. Por favor, elimínelo y agréguelo de nuevo si desea cambiar la cantidad.');
                    } else {
                        productosEnLista.push({
                            id: productoEncontrado.id,
                            nombre: productoEncontrado.nombre,
                            codigo: productoEncontrado.codigo,
                            precio: productoEncontrado.precio,
                            cantidad: cantidad
                        });
                        actualizarListaVisual();
                        codigoInput.value = '';
                        cantidadInput.value = '';
                    }
                } else {
                    alert('Producto no encontrado.');
                }
            });

            function actualizarListaVisual() {
                listaProductosUl.innerHTML = '';
                productosEnLista.forEach((p, index) => {
                    const li = document.createElement('tr');
                    let total = p.precio * p.cantidad;
                    li.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">${p.nombre}</div>                            
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">${p.codigo}</div>                            
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">${p.precio}</div>                            
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">${p.cantidad}</div>                            
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">${total}</div>                            
                        </td>
                        <button type="button" class="remove-item-btn" data-index="${index}">&times;</button>
                    `;
                    listaProductosUl.appendChild(li);
                });
            }

            listaProductosUl.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-item-btn')) {
                    const index = e.target.getAttribute('data-index');
                    productosEnLista.splice(index, 1);
                    actualizarListaVisual();
                }
            });

            mainForm.addEventListener('submit', () => {
                listaProductosInput.value = JSON.stringify(productosEnLista);
            });
        });
    </script>
</body>
</html>