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
			// Configura los datos de tu cuenta
			include('../config.php');
			// Conectar a la base de datos
			$connection = mysqli_connect($dbhost, $dbusername, $dbuserpass, $dbname);
			if (mysqli_connect_errno()) {
				openBody();
				printError(mysqli_connect_error());
				echo '<a style="margin-top: 20px;text-align:center;" href="../admin/">Volver al panel de administración</a>';
				closeBody();
				return;
			}
			//	Set collation
			mysqli_set_charset($connection, "utf8");
			//	Comprueba que se ha recibido algo
			if(isset($_FILES['inputFile']) && !empty($_FILES['inputFile']['name']) && $_POST['inputName'] && $_POST['inputCategory']) {
				//	Datos del archivo subido
				$nombre = $_FILES['inputFile']['name'];
				$nombre_tmp = $_FILES['inputFile']['tmp_name'];
				$tipo = $_FILES['inputFile']['type'];
				$tamano = $_FILES['inputFile']['size'];
				
				$ext_permitidas = array('jpg');
				$partes_nombre = explode('.', $nombre);
				$extension = end($partes_nombre);
		                $extension = strtolower($extension);
				$ext_correcta = in_array($extension, $ext_permitidas);
				$tipo_correcto = preg_match('/^image\/(jpeg|jpg)$/', $tipo);
				
				$limite = 1000 * 1000;

				//	Averiguar la ruta de la categoría correspondiente
				$query = 'SELECT * FROM categories WHERE id = ' . $_POST['inputCategory'];
				$result = $connection -> query($query);
				$category = $result -> fetch_array(MYSQLI_ASSOC);
				$originals_target_path = realpath(getcwd() . '/../');
				$originals_target_path .= '/img/' . $category['location'];
				$miniatures_target_path = $originals_target_path . '/miniaturas';
				$originals_target_filename = $originals_target_path . '/' . $_POST['inputName'] . '.jpg';
				$miniatures_target_filename = $miniatures_target_path . '/' . $_POST['inputName'] . '.jpg';
				date_default_timezone_set('Europe/Madrid');
				$date = date('Y/m/d H:i:s');

				if($ext_correcta && $tipo_correcto && $tamano <= $limite){
					if($_FILES['inputFile']['error'] <= 0) {
						if(file_exists($originals_target_filename)) {
							openBody();
							printError('Ya existe la imagen ' . $originals_target_filename . '.<br>Por favor, escoge otro nombre de archivo.');
							echo '<a style="margin-top: 20px;text-align:center;" href="../admin/">Volver al panel de administración</a>';
							closeBody();
						}
						else {
							//	Sube la imagen en calidad original si todo es correcto
							move_uploaded_file($nombre_tmp, $originals_target_filename);
							//	Crear variable de imagen a partir de la original
							$original = imagecreatefromjpeg($originals_target_filename);
							//	Definir tamaño máximo y mínimo
							$max_ancho = 160;
							$max_alto = 160;
							//	Recoger ancho y alto de la original
							list($ancho,$alto) = getimagesize($originals_target_filename);
							//	Calcular proporción ancho y alto
							$x_ratio = $max_ancho / $ancho;
							$y_ratio = $max_alto / $alto;
							if(($ancho <= $max_ancho) && ($alto <= $max_alto)) {
							//	Si es más pequeña que el máximo no redimensionamos
								$ancho_final = $ancho;
								$alto_final = $alto;
							}
							//	Si no, redimensionamos
							else {
								$alto_final = ceil($x_ratio * $alto);
								$ancho_final = $max_ancho;
							}
							//	Crear lienzo en blanco con proporciones
							$lienzo = imagecreatetruecolor($ancho_final, $alto_final);
							//	Copiar $original sobre la imagen que acabamos de crear en blanco ($tmp)
							imagecopyresampled($lienzo, $original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);
							//	Limpiar memoria
							imagedestroy($original);
							//	Definimos la calidad de la imagen final
							$cal = 100;
							//	Se crea la imagen final en el directorio indicado
							imagejpeg($lienzo, $miniatures_target_filename, $cal);					
							//	Log action
							$description = $user['name'] . ' subió la imagen \'' . $originals_target_filename . '\'';
							mysqli_query($connection, 'INSERT INTO logs(idUser, idAction, ip, createdAt, description) VALUES ("' . $user['id'] . '", 1, "' . $_SERVER['REMOTE_ADDR'] . '", "' . $date . '", "' . $description . '")') or die(mysqli_error());
							//	Redirige
							openBody();
							printInfo('La imagen se subió correctamente a ' . $originals_target_filename);
							echo '<a style="margin-top: 20px;text-align:center;" href="../admin/">Volver al panel de administración</a>';
							closeBody();
						}
					}
					else {
						openBody();
						printError($_FILES['inputFile']['error']);
						echo '<a style="margin-top: 20px;text-align:center;" href="../admin/">Volver al panel de administración</a>';
						closeBody();
					}
				}
				else {
					openBody();
					printError('La imagen no cumple con los requisitos (extensión JPG, y tamaño máximo 1 MB)');
					echo '<a style="margin-top: 20px;text-align:center;" href="../admin/">Volver al panel de administración</a>';
					closeBody();
				}
			}
			//	Cerrar conexión
			mysqli_close($connection);
		}
		else {
			openBody();
			printError($errorNoAdmin);
			closeBody();
		}
	}
	else {
		openBody();
		printError($errorNoUser);
		closeBody();
	}
?>