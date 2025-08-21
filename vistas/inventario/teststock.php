<?php
include '..\..\db\db.php';
// **IMPORTANTE**: Verifica si existe una petición para limpiar el mensaje
if (isset($_POST['limpiar_mensaje'])) {
    unset($_SESSION['mensaje_exito']);
    unset($_SESSION['mensaje_error']);
    // Redirige para evitar que el POST se reenvíe al recargar
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (tu lógica de procesamiento de stock) ...

    try {
        // ... (tu lógica de transacciones) ...

        // Si la operación es exitosa
        mysqli_commit($conn);
        $_SESSION['mensaje_exito'] = "Operación de stock realizada con éxito.";
        
    } catch (Exception $e) {
        // Si hay un error
        mysqli_rollback($conn);
        $_SESSION['mensaje_error'] = $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['procesar_movimiento'])) {
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $lista_productos = json_decode($_POST['lista_productos'], true);

    // Iniciar la transacción para asegurar la consistencia de los datos
    mysqli_begin_transaction($conn);
    $todo_ok = true;
    $mensaje_error = '';

    if (!is_array($lista_productos) || empty($lista_productos)) {
        $todo_ok = false;
        $mensaje_error = "No hay productos en la lista para procesar.";
    }

    if ($todo_ok) {
        foreach ($lista_productos as $producto) {
            $id_producto = $producto['id'];
            $cantidad = (int)$producto['cantidad'];

            if ($cantidad <= 0) {
                $todo_ok = false;
                $mensaje_error = "La cantidad para el producto " . $producto['nombre'] . " debe ser un número positivo.";
                break;
            }

            try {
                // Obtener el ID del inventario y la cantidad actual del producto
                $sql_inventario = "SELECT id, cantidad FROM inventario WHERE id_producto = ?";
                $stmt_inventario = mysqli_prepare($conn, $sql_inventario);
                mysqli_stmt_bind_param($stmt_inventario, "i", $id_producto);
                mysqli_stmt_execute($stmt_inventario);
                $result = mysqli_stmt_get_result($stmt_inventario);
                
                if (mysqli_num_rows($result) === 0) {
                    throw new Exception("Producto con ID '" . $id_producto . "' no encontrado en el inventario.");
                }
                $row = mysqli_fetch_assoc($result);
                $id_inventario = $row['id'];
                $cantidad_actual = $row['cantidad'];
        
                // Calcular la nueva cantidad según el tipo de movimiento
                $nueva_cantidad = $cantidad_actual;
                if ($tipo_movimiento === "entrada") {
                    $nueva_cantidad += $cantidad;
                } elseif ($tipo_movimiento === "salida") {
                    if ($cantidad_actual < $cantidad) {
                        throw new Exception("Stock insuficiente para " . $producto['nombre'] . ". Disponible: $cantidad_actual");
                    }
                    $nueva_cantidad -= $cantidad;
                }
                
                // Actualizar la cantidad en la tabla 'Inventario'
                $sql_update = "UPDATE inventario SET cantidad = ? WHERE id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "ii", $nueva_cantidad, $id_inventario);
                if (!mysqli_stmt_execute($stmt_update)) {
                    throw new Exception("Error al actualizar el inventario para el producto " . $producto['nombre'] . ".");
                }
        
                // Registrar el movimiento en la tabla 'movimiento_inventario'
                $sql_movimiento = "INSERT INTO movimientos_inventario (id_inv, tipo, cantidad, fecha, cantidad_actual) VALUES (?, ?, ?, NOW(), ?)";
                $stmt_movimiento = mysqli_prepare($conn, $sql_movimiento);
                mysqli_stmt_bind_param($stmt_movimiento, "isii", $id_inventario, $tipo_movimiento, $cantidad, $nueva_cantidad);
                if (!mysqli_stmt_execute($stmt_movimiento)) {
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
            $mensaje_exito = "Todas las operaciones de stock realizadas con éxito.";
        } else {
            mysqli_rollback($conn);
        }
    }
}

// Obtener la lista de productos para el autocompletado y búsqueda
$productos_json = "[]";
$query_productos = mysqli_query($conn, "SELECT id, nombre, codigo FROM producto");
if (mysqli_num_rows($query_productos) > 0) {
    $productos_data = [];
    while ($row = mysqli_fetch_assoc($query_productos)) {
        $productos_data[] = $row;
    }
    $productos_json = json_encode($productos_data);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Stock Masiva</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../style2.css">
</head>
<body>
    <div class="pt-16 pb-8"> 
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
    <!-- Header 
    <div class="md:flex md:items-center md:justify-between mb-6">
    <div class="flex-1 min-w-0">-->
    <div class="container">
        <h1>Gestión de Stock</h1>
        
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

        <form id="add-form">
            <input type="text" id="codigo_producto" placeholder="Código de Producto" list="codigos" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
            <input type="number" id="cantidad_producto" placeholder="Cantidad" min="1" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
            <button type="submit" id="add-btn" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Agregar</button>
            <datalist id="codigos">
                </datalist>
        </form>

        <form id="main-form" action="teststock.php" method="POST">
            <div class="form-group">
                <label for="tipo_movimiento">Tipo de Movimiento:</label>
                <select id="tipo_movimiento" name="tipo_movimiento" required>
                    <option value="entrada">Carga (Entrada)</option>
                    <option value="salida">Descarga (Salida)</option>
                </select>
            </div>
            
            <h2>Lista de Productos</h2>
            <div class="lista-productos">
                <ul id="product-list">
                    </ul>
            </div>
            
            <input type="hidden" name="lista_productos" id="lista_productos">
            <button type="submit" name="procesar_movimiento">Procesar Movimiento</button>
        </form>
    </div>
<!--</div>
</div>-->
</div>
</div>
 </div> 
    <script type="text/javascript" src="../../js/main.js"></script>
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
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span>${p.nombre} (${p.codigo}) - Cantidad: ${p.cantidad}</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.querySelector('.close-btn');

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    // Crea un formulario dinámicamente
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    
                    // Agrega un campo oculto que la lógica de PHP detectará
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'limpiar_mensaje';
                    input.value = '1';
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    
                    // Envía el formulario para limpiar la sesión
                    form.submit();
                });
            }
        });
    </script>
</body>
</html>