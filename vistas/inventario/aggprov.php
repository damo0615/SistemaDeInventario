<?php
    session_start();
    include '..\php_inyec.php';
    include '../../sesion_time.php';
    include '..\..\db\db.php';
    $user_id = $_SESSION['id'];
    if(!isset($user_id)) {
        header("location:../../sesion/login.php");
    }else{
        echo "<script>console.log(".$user_id.")</script>";
    }
    if(!empty($_POST['send']) && !empty($_POST['name-prov'])&& !empty($_POST['cod-prov'])&& !empty($_POST['dir-prov']&& !empty($_POST['obser-prov']))){
        $name = limpiar_cadena($_POST['name-prov']);
        $codigo = limpiar_cadena($_POST['cod-prov']);
        $dir = limpiar_cadena($_POST['dir-prov']);
        $obser = limpiar_cadena($_POST['obser-prov']);
        if (!isset($_SESSION['mensaje_sql'])) {
            $codi = mysqli_query($conn, "SELECT * FROM proveedor WHERE codigop='$codigo'");
            if($codi -> num_rows > 0){
               $_SESSION['mensaje_error'] = 'El codigo de proveedor ya existe, intente con otro';
               header('location:proveedores.php');
            }else{
                $query = mysqli_query($conn, "INSERT INTO proveedor (nombrep, codigop, direccion, observacion) VALUES ('$name','$codigo','$dir','$obser')");
                if(!$query){
                    die("Query Failed");
                }
                $id_user = $_SESSION['id'];
                $accion = 'El usuario '.$_SESSION['usern'].' ha registrado un nuevo proveedor';
                $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
                if(!$bitacora){
                    die("Query Failed");
                }
                $_SESSION['mensaje_exito'] = 'Proveedor agregado con exito';
                header('location:proveedores.php');
            }
        }
    }
?>