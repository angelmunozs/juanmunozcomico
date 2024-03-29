<?php
	//	Inciar sesión
	session_start();
	//	Importar funciones y mensajes
	include('../messages.php');
	//	Function to delete a directory
	function deleteDir($dirPath) {
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				deleteDir($file);
			}
			else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
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
				//	Get data from categories list
				$query1 = 'SELECT * FROM categories WHERE id = ' . $_POST['category'];
				$result1 = $connection -> query($query1);
				$category = $result1 -> fetch_array(MYSQLI_ASSOC);
				//	Delete from categories
				$query2 = 'DELETE FROM categories WHERE id = ' . $_POST['category'];
				$result2 = $connection -> query($query2);
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