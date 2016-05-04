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
			if($_POST['old_category'] && $_POST['category'] && $_POST['location'] && ($_POST['disabled'] == 0 || $_POST['disabled'] == 1)) {
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
				//	Get old data
				$query = 'SELECT * FROM categories WHERE id = ' . $_POST['old_category'];
				$result = $connection -> query($query);
				$old_category = $result -> fetch_array(MYSQLI_ASSOC);
				//	Update category
				mysqli_query($connection, 'UPDATE categories SET category = "' . $_POST['category'] . '", location="' . $_POST['location'] . '", disabled="' . $_POST['disabled'] . '", updatedAt="' . $date . '" WHERE id=' . $_POST['old_category']);
				//	Update directory name
				$olddir = realpath(getcwd() . '/../');
				$olddir .= '/img/' . $old_category['location'];
				$newdir = realpath(getcwd() . '/../');
				$newdir .= '/img/' . $_POST['location'];
				rename($olddir, $newdir);
				//	Log action
				$description = $user['name'] . ' actualizó la categoría ' . $old_category['category'];
				mysqli_query($connection, 'INSERT INTO logs(idUser, idAction, ip, createdAt, description) VALUES ("' . $user['id'] . '", 4, "' . $_SERVER['REMOTE_ADDR'] . '", "' . $date . '", "' . $description . '")');
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