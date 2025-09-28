<?php 
  	session_start();
 	include '../../db/db.php';
 	include '../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = mysqli_query($conn,"SELECT id,password FROM usuario WHERE id='$userid'");
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave  = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT id_cliente FROM compra WHERE id_cliente='$id'");
				if($comp = $query->fetch_object()){
					$_SESSION['mensaje_error'] = "No puede borrar el cliente porque hay registros asociadas a esta";
					header('location:../clientes.php');
				}else{
					$borrar = mysqli_query($conn,"DELETE FROM clientes WHERE id='$id'");
					if(!$borrar){
		                die("Query Failed");
		            }
		            $id_user = $_SESSION['id'];
		            $accion = 'El usuario '.$_SESSION['usern'].' ha borrado un cliente';
		            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
		            if(!$bitacora){
		                die("Query Failed");
		            }
		            $_SESSION['mensaje_exito'] = "Cliente borrado con exito";
		            header('location:../clientes.php');
				}
			}else{
				$_SESSION['mensaje_error'] = "La contraseña no coincide";
				header('location:../../clientes.php');
			}
		}
 	}else{
			header('location:../clientes.php');
	}
?>