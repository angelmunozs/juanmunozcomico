<?php
	//	Inciar sesión
	session_start();
	//	Importar funciones y mensajes
	include('../messages.php');
	//	Comprobar usuario
	if($_SESSION['user']) {
		$user = $_SESSION['user'];
		if($user['admin'] == 1) {
			if($_POST['category'] && $_POST['location'] && ($_POST['disabled'] == 0 || $_POST['disabled'] == 1)) {
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
				//	Insert into categories
				mysqli_query($connection, 'INSERT INTO categories(category, location, createdAt, disabled) VALUES ("' . $_POST['category'] . '", "' . $_POST['location'] . '", "' . $date . '", "' . $_POST['disabled'] . '")');
				//	Create directory
				$newdir = realpath(getcwd() . '/../');
				$newdir .= '/img/' . $_POST['location'];
				//	Directorio originales
				mkdir($newdir);
				$newdir .= '/miniaturas';
				//	Directorio miniaturas
				mkdir($newdir);
				//	Log action
				$description = $user['name'] . ' creó la categoría \'' . $_POST['category'] . '\'';
				mysqli_query($connection, 'INSERT INTO logs(idUser, idAction, ip, createdAt, description) VALUES ("' . $user['id'] . '", 3, "' . $_SERVER['REMOTE_ADDR'] . '", "' . $date . '", "' . $description . '")');
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