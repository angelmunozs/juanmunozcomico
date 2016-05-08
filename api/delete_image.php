<?php
	//	Inciar sesión
	session_start();
	//	Importar funciones y mensajes
	include('../messages.php');
	//	Comprobar usuario
	if($_SESSION['user']) {
		$user = $_SESSION['user'];
		if($user['admin'] == 1) {
			if($_POST['location']) {
				// Configura los datos de tu cuenta
				include('../config.php');
				// Conectar a la base de datos
				$connection = mysqli_connect($dbhost, $dbusername, $dbuserpass, $dbname);
				if (mysqli_connect_errno()) {
					$response = array(
						'error' => mysqli_connect_error()
					);
					echo json_encode($response);
					return;
				}
				//	Set collation
				mysqli_set_charset($connection, "utf8");
				//	Set date
				date_default_timezone_set('Europe/Madrid');
				$date = date('Y/m/d H:i:s');
				//	Directorio
				$miniature_location = realpath(getcwd() . '/../');
				$miniature_location .= '/' . $_POST['location'];
				$original_location = str_replace('miniaturas/', '', $miniature_location);
				unlink($miniature_location);
				unlink($original_location);
				//	Log action
				$description = $user['name'] . ' eliminó la imagen \'' . $original_location . '\'';
				mysqli_query($connection, 'INSERT INTO logs(idUser, idAction, ip, createdAt, description) VALUES ("' . $user['id'] . '", 2, "' . $_SERVER['REMOTE_ADDR'] . '", "' . $date . '", "' . $description . '")');
				//	Cerrar conexión
				mysqli_close($connection);
				//	Retrieve result
				echo true;
			}
			else {
				$response = array(
					'error' => $errorNoArguments
				);
				echo json_encode($response);
			}
		}
		else {
			$response = array(
				'error' => $errorNoAdmin
			);
			echo json_encode($response);
		}
	}
	else {
		$response = array(
			'error' => $errorNoUser
		);
		echo json_encode($response);
	}
?>