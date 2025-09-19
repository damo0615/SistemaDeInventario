  <?php 
  	session_start();
 	include '../../db/db.php';
 	include '../php_inyec.php';
 	$userid = $_SESSION['id'];
 	$query = mysqli_query($conn,"SELECT * FROM usuario WHERE id='$userid'");
 	if(isset($_POST['send'])){
    	if ($comp = $query->fetch_object()) {
    		$hash = $comp->password;
			$clave  = limpiar_cadena($_POST['clave']);
			$id = limpiar_cadena($_POST['id']);
			if (password_verify($clave,$hash)) {
				$query = mysqli_query($conn,"SELECT * FROM inventario WHERE id_producto='$id'");
				if($comp = $query->fetch_object()){
					echo "No se puede borrar el producto porque hay registros asociadas a este";
				}else{
					$borrar = mysqli_query($conn,"DELETE FROM producto WHERE id='$id'");
					if(!$borrar){
		                die("Query Failed");
		            }
		            header('location:tags.php');
				}
			}else{
				echo 'la contraseña esta errada';
			}
		}
 	}else{
			header('location:tags.php');
	}
?>