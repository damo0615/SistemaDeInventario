<?php

// 1. Configuración de la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// 2. Recibir los datos del formulario
$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$precio = $_POST['precio'];
$id_proveedor = $_POST['id_proveedor'];
$id_tag = $_POST['id_tag'];
$descripcion = $_POST['descripcion'];
$cantidad_inventario = $_POST['cantidad'];

// 3. Inserción en la tabla `productos`
$sql_productos = "INSERT INTO productos (nombre, codigo, precio, id_proveedor, id_tag, descripcion) 
                  VALUES ('$nombre', '$codigo', '$precio', '$id_proveedor', '$id_tag', '$descripcion')";

if ($conn->query($sql_productos) === TRUE) {
    // 4. Obtener el ID del producto recién insertado
    $id_producto = $conn->insert_id;

    // 5. Inserción en la tabla `inventario` usando el nuevo ID
    $sql_inventario = "INSERT INTO inventario (id_producto, cantidad) 
                       VALUES ('$id_producto', '$cantidad_inventario')";

    if ($conn->query($sql_inventario) === TRUE) {
        echo "Nuevo producto registrado exitosamente en ambas tablas.";
    } else {
        echo "Error al registrar en inventario: " . $conn->error;
    }

} else {
    echo "Error al registrar en productos: " . $conn->error;
}

// 6. Cerrar la conexión
$conn->close();

?>