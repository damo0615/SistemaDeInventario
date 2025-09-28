<?php
    include '../../sesion_time.php';
    include '../../vistas/php_inyec.php';
    include '..\..\db\db.php';

    session_start();
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }
    $prove = mysqli_query($conn, "SELECT id,nombrep FROM proveedor");
    $tag = mysqli_query($conn, "SELECT id,nombres FROM tag");

    if(!empty($_POST['send'])){
        $name = limpiar_cadena($_POST['product-name']);
        $categoria = limpiar_cadena($_POST['product-category']);
        $precio = limpiar_cadena($_POST['product-price']);
        $codigo = limpiar_cadena($_POST['product-codigo']);
        $prove = limpiar_cadena($_POST['product-prov']);
        $descrip = $_POST['product-descrip'];
        if (!isset($_SESSION['mensaje_sql'])) {
            $codi = mysqli_query($conn, "SELECT * FROM producto WHERE codigo='$codigo'");
            if($codi -> num_rows > 0){
               $_SESSION['mensaje_error'] = 'El codigo de producto ya existe, intente con otro';
               header('location:../inventario.php');
            }else{
                try {
                mysqli_begin_transaction($conn);
                $query = mysqli_query($conn, "INSERT INTO producto (nombre, codigo, precio, id_tag, id_proveedor, descripcion) VALUES ('$name','$codigo','$precio','$categoria','$prove','$descrip')");
                if(!$query){
                    die("Query Failed");
                }
                $id_producto = mysqli_insert_id($conn);
                $inv = mysqli_query($conn, "INSERT INTO inventario (id_producto,cantidad) VALUES ('$id_producto',0)");
                if(!$inv){
                    die("Query Failed");
                }
                mysqli_commit($conn);   
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    echo "Error: " . $e->getMessage();
                }
                $id_user = $_SESSION['id'];
                $accion = 'El usuario '.$_SESSION['usern'].' ha registrado un nuevo producto';
                $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                if(!$bitacora){
                    die("Query Failed");
                }
                $_SESSION['mensaje_exito'] = 'Producto Guardado con exito!';
                header('location:../inventario.php');
            }
        }
        
    }
?>