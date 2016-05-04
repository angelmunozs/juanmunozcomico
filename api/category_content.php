<?php
	//	Inciar sesión
	session_start();
	//	Importar funciones y mensajes
	include('../functions.php');
	include('../messages.php');
	//	Comprobar usuario
	if($_GET['id']) {
		// Configura los datos de tu cuenta
		include('../config.php');
		// Conectar a la base de datos
		$connection = mysqli_connect($dbhost, $dbusername, $dbuserpass, $dbname);
		if (mysqli_connect_errno()) {
			$response = array(
				'error' => mysqli_connect_error()
			);
			echo json_encode($response);
		}
		//	Set collation
		mysqli_set_charset($connection, "utf8");
		//	Set date
		date_default_timezone_set('Europe/Madrid');
		$date = date('Y/m/d H:i:s');
		//	Insert into categories
		$query = 'SELECT location, category FROM categories WHERE id = ' . $_GET['id'];
		$result = $connection -> query($query);
		//	Skip if no rows found
		if(mysqli_num_rows($result) < 1) {
			$response = array(
				'error' => $errorNoResults
			);
			echo json_encode($response);
		}
		else {
			$category = $result -> fetch_array(MYSQLI_ASSOC);
			//	Create directory
			$dir = realpath(getcwd() . '/../');
			$dir .= '/img/' . $category['location'];
			//	Directorio originales
			$content = scandir($dir);
			$filtered_content = array();
			//	Si hay más archivos aparte de '.' y '..'
			if(count($content) > 2) {
				for ($i = 0; $i < count($content); $i++) {
					if(preg_match("/.jpg/", $content[$i])) {
						array_push($filtered_content, 'img/' . $category['location'] . '/miniaturas/' . $content[$i]);
					}
				}
			}
			//	Cerrar conexión
			mysqli_close($connection);
			//	Retrieve result
			echo json_encode($filtered_content);
		}
	}
	else {
		$response = array(
			'error' => $errorNoArguments
		);
		echo json_encode($response);
	}
?>