  <?php 
  	session_start();
 	include '../../../db/db.php';
 	include '../../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = mysqli_query($conn,"SELECT id,password FROM usuario WHERE id='$userid'");
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave  = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT id_producto FROM detalles_compra WHERE id_producto='$id'");
				if($comp = $query->fetch_object()){
					$_SESSION['mensaje_error'] = "No se puede borrar el producto porque hay registros asociadas a este";
					header('location:../../inventario.php');
				}else{
					$query = mysqli_query($conn,"SELECT id_inv FROM movimientos_inventario WHERE id_inv='$id'");
					if($comp = $query->fetch_object()){
						$_SESSION['mensaje_error'] = "No se puede borrar el producto porque hay registros asociadas a este";
						header('location:../../inventario.php');
					}else{
						$borrar = mysqli_query($conn,"DELETE FROM producto WHERE id='$id'");
						if(!$borrar){
			                die("Query Failed");
			            }
			            $id_user = $_SESSION['id'];
			            $accion = 'El usuario '.$_SESSION['usern'].' ha borrado un producto';
			            $bitacora = mysqli_query($conn, "INSERT INTO bitacora (accion,id_user) VALUES ('$accion','$id_user')");
			            if(!$bitacora){
			                die("Query Failed");
			            }
						            $_SESSION['mensaje_exito'] = "producto eliminado con exito";
			             header('location:../../inventario.php');
					}
				}
			}else{
				$_SESSION['mensaje_error'] = 'la contraseña esta errada';
				header('location:../../inventario.php');
			}
		}
 	}else{
			header('location:../../inventario.php');
	}
?>