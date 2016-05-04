<?php
	//	Inciar sesión
	session_start();
	//	Importar funciones y mensajes
	include('../functions.php');
	include('../messages.php');
	//	Comprobar usuario
	if($_SESSION['user']) {
		$user = $_SESSION['user'];
		if($user['admin'] == 1) {
			if($_POST['category']) {
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
				//	Delete from categories
				mysqli_query($connection, 'DELETE FROM categories WHERE id = ' . $_POST['category']);
				//	Get data from categories list
				$query = 'SELECT * FROM categories WHERE id = ' . $_POST['category'];
				$result = $connection -> query($query);
				$category = $result -> fetch_array(MYSQLI_ASSOC);
				//	Delete directory
				$olddir = realpath(getcwd() . '/../');
				$olddir .= '/img/' . $category['location'];
				deleteDir($olddir);
				//	Log action
				$description = $user['name'] . ' eliminó la categoría ' . $category['category'] . ' y todo su contenido';
				mysqli_query($connection, 'INSERT INTO logs(idUser, idAction, ip, createdAt, description) VALUES ("' . $user['id'] . '", 5, "' . $_SERVER['REMOTE_ADDR'] . '", "' . $date . '", "' . $description . '")');
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