<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- Title -->
		<title>Juan Muñoz | Panel de administración</title>

		<!-- Favicon -->
		<link rel="shortcut icon" href="cara1.ico" />

		<!-- Metas -->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Página oficial del cómico Juan MuÃ±oz">
		<meta name="keywords" content="cruz y raya, juan muñoz">
		<meta name="author" content="Ángel Muñoz Sagaseta de Ylurdoz, @angelmunozs">
			
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="../components/bootstrap-3.3.5/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" type="text/css" href="../components/font-awesome-4.3.0/css/font-awesome.min.css">
		<!-- Own CSS -->
		<link rel="stylesheet" type="text/css" href="../css/admin.css">

		<!-- jQuery -->
		<script type="text/javascript" src="../components/jquery/jquery-2.1.4.min.js"></script>
		<!-- Bootstrap -->
		<script type="text/javascript" src="../components/bootstrap-3.3.5/dist/js/bootstrap.min.js"></script>
		<!-- Own JS -->
		<script type="text/javascript" src="../js/admin.js"></script>

	</head>

	<body>

		<?php
			// Configura los datos de tu cuenta
			include('../config.php');
			include('../functions.php');
			session_start();
			// Conectar a la base de datos
			$connection = mysqli_connect($dbhost, $dbusername, $dbuserpass, $dbname);
			if (mysqli_connect_errno()) {
				printError(mysqli_connect_error());
			}
			//	Set collation
			mysqli_set_charset($connection, "utf8");

			//	Sacar algunos datos
			$categories1 = mysqli_query($connection, "SELECT * FROM categories");
			$categories2 = mysqli_query($connection, "SELECT * FROM categories");
			$categories3 = mysqli_query($connection, "SELECT * FROM categories");
			$categories4 = mysqli_query($connection, "SELECT * FROM categories");
			$logs = mysqli_query($connection, "SELECT description, createdAt, ip, idUser FROM logs ORDER BY createdAt DESC LIMIT 8 OFFSET 0");

			$user = $_SESSION['user'];

			$html_admin = '<div class="container-fluid">
								<div class="row content">
									<div class="col-sm-2 sidenav">
										<div class="user-info">
											<div class="user-image">
												<img src="../img/users/' . $user['id'] . '.png"></img>
											</div>
											<div class="user-else">
												<div class="user-name">
													' . $user['name'] . '<span class="logout"><a href="../api/logout.php">Cerrar sesión</a></span>
												</div>
												<div class="user-state">
													<span><i class="fa fa-circle"></i> Online</span>
												</div>
											</div>
											
										</div>
										<h3>Panel de control</h3>
										<ul class="nav nav-pills nav-stacked">
											<li class="section-link active"><a href="#section1" id="section1-link"><i class="fa fa-dashboard fa-left"></i>Actividad</a></li>
											<li class="section-link"><a href="#section2" id="section2-link"><i class="fa fa-upload fa-left"></i>Subir una imagen</a></li>
											<li class="section-link"><a href="#section3" id="section3-link"><i class="fa fa-times fa-left"></i>Eliminar una imagen</a></li>
											<li class="section-link"><a href="#section4" id="section4-link"><i class="fa fa-plus fa-left"></i>Crear una categoría</a></li>
											<li class="section-link"><a href="#section5" id="section5-link"><i class="fa fa-pencil fa-left"></i>Editar una categoría</a></li>
											<li class="section-link"><a href="#section6" id="section6-link"><i class="fa fa-times fa-left"></i>Eliminar una categoría</a></li>
										</ul><br>
									</div>

									<div class="col-sm-10" style="padding: 0px 30px;">
										<div id="section1" class="section-content">
											<h4 class="hidden-xs"><small>ACTIVIDAD</small></h4>
											<hr>
											<h2><i class="fa fa-dashboard"></i> Actividad (mostrando 8 últimos)</h2>
											<div class="actividad">';

											while ($actividad = mysqli_fetch_array($logs, MYSQL_NUM)) {

												$partes = explode(' ', $actividad[1]);
												$fecha = $partes[0];
												$hora = $partes[1];

												$html_admin .= '<div class="actividad-item">
																	<div class="actividad-image">
																		<img src="../img/users/' . $actividad[3] . '.png"></img>
																	</div>
																	<div class="actividad-text">
																		<div class="actividad-item-title">'. $actividad[0] . '</div>
																		<div class="actividad-item-desc"><i class="fa fa-clock-o"></i> '. $fecha . ' a las '. $hora . '</div>
																	</div>
																</div>';
											}

			$html_admin .= '				</div>
										</div>
										<div id="section2" class="hidden section-content">
											<h4 class="hidden-xs"><small>SUBIR UNA IMAGEN</small></h4>
											<hr>
											<h2><i class="fa fa-upload fa-left"></i>Subir una imagen (formato JPG y tamaño máximo de archivo 2MB)</h2>
											<div class="cajita">
												<form enctype="multipart/form-data" method="post" action="../api/upload_image.php">
													<div class="form-group">
														<label class="compulsory" for="inputFile">Selecciona un archivo</label>
														<input id="inputFile" name="inputFile" type="file">
													</div>
													<div class="form-group">
														<label class="compulsory" for="inputCategory">Selecciona la categoría</label>
														<select id="inputCategory" name="inputCategory" class="form-control">';

															while ($category1 = mysqli_fetch_array($categories1, MYSQL_NUM)) {
																$html_admin .= '<option value="' . $category1[0] . '">' . $category1[1] . '</option>';
															}

			$html_admin .= '
														</select>
													</div>
													<div class="form-group">
														<label class="compulsory" for="inputName">Nombre de la imagen</label>
														<input id="inputName" name="inputName" type="text" placeholder="Sin incluir la extensión" class="form-control">
													</div>
													<div class="form-group" style="margin-top: 20px;">
														<button type="submit" class="btn btn-primary">Subir imagen</button>
													</div>
												</form>
											</div>
										</div>
										<div id="section3" class="hidden section-content">
											<h4 class="hidden-xs"><small>ELIMINAR UNA IMAGEN</small></h4>
											<hr>
											<h2><i class="fa fa-times fa-left"></i>Eliminar una imagen</h2>
											<div class="cajita">
												<div class="form-group">
													<label class="compulsory" for="deleteImageCategory">Selecciona la categoría</label>
													<select id="deleteImageCategory" name="deleteImageCategory" class="form-control">';

														while ($category2 = mysqli_fetch_array($categories2, MYSQL_NUM)) {
															$html_admin .= '<option value="' . $category2[0] . '">' . $category2[1] . '</option>';
														}

			$html_admin .= '
													</select>
												</div>
												<div class="form-group">
													<label class="compulsory" for="deleteImageName">Selecciona la imagen</label>
													<select id="deleteImageName" name="deleteImageName" class="form-control">';
			$html_admin .= '
													</select>
												</div>
												<div id="deleteImageShow"></div>
												<div class="checkbox">
													<label class="compulsory">
														<input type="checkbox" id="sureDeleteImage">
														 Estoy seguro de que quiero eliminar la categoría y su contenido definitivamente
													</label>
												</div>
												<div class="form-group" style="margin-top: 20px;">												
													<button type="button" id="deleteImage" class="btn btn-primary">Eliminar imagen</button>
													<div style="margin-top: 20px;" id="deleteImageError"></div>
												</div>
											</div>
										</div>
										<div id="section4" class="hidden section-content">
											<h4 class="hidden-xs"><small>CREAR UNA CATEGORÍA</small></h4>
											<hr>
											<h2><i class="fa fa-plus fa-left"></i>Crear una categoría</h2>
											<div class="cajita">
												<div class="form-group">
													<label class="compulsory" for="newCategory">Nombre de la categoría</label>
													<input id="newCategory" name="newCategory" type="text" placeholder="Por ejemplo: Gira 2016" class="form-control">
												</div>
												<div class="form-group">
													<label class="compulsory" for="newLocation">Carpeta destino en el servidor</label>
													<input id="newLocation" name="newLocation" type="text" placeholder="Nombre de la carpeta sin espacios, comas, puntos ni nada por el estilo (Por ejemplo: gira2016)" class="form-control">
												</div>
												<div class="form-group">
													<label class="compulsory" for="newDisabled">Se muestra en la web</label>
													<select id="newDisabled" name="newDisabled" class="form-control">
														<option value="0">Sí</option>
														<option value="1">No</option>
													</select>
												</div>
												<div class="form-group" style="margin-top: 20px;">
													<button id="createCategory" type="button" class="btn btn-primary">Crear categoría</button>
													<div style="margin-top: 20px;" id="createCategoryError"></div>
												</div>
											</div>
										</div>
										<div id="section5" class="hidden section-content">
											<h4 class="hidden-xs"><small>EDITAR UNA CATEGORÍA</small></h4>
											<hr>
											<h2><i class="fa fa-pencil fa-left"></i>Editar una categoría</h2>
											<div class="cajita">
												<div class="form-group">
													<label class="compulsory" for="oldCategory">Selecciona la categoría</label>
													<select id="oldCategory" name="oldCategory" class="form-control">';

														while ($category3 = mysqli_fetch_array($categories3, MYSQL_NUM)) {
															$html_admin .= '<option value="' . $category3[0] . '">' . $category3[1] . '</option>';
														}

			$html_admin .= '
													</select>
												</div>
												<div class="form-group">
													<label class="compulsory" for="updateCategory">Nombre de la categoría</label>
													<input id="updateCategory" name="updateCategory" type="text" placeholder="Por ejemplo: Gira 2016" class="form-control">
												</div>
												<div class="form-group">
													<label class="compulsory" for="updateLocation">Carpeta destino en el servidor</label>
													<input id="updateLocation" name="updateLocation" type="text" placeholder="\'img/\' seguido del nombre sin espacios, comas, puntos ni nada por el estilo (Por ejemplo: img/gira2016)" class="form-control">
												</div>
												<div class="form-group">
													<label class="compulsory" for="updateDisabled">Se muestra en la web</label>
													<select id="updateDisabled" name="updateDisabled" class="form-control">
														<option value="0">Sí</option>
														<option value="1">No</option>
													</select>
												</div>
												<div class="form-group" style="margin-top: 20px;">												
													<button type="button" id="editCategory" class="btn btn-primary">Actualizar categoría</button>
													<div style="margin-top: 20px;" id="updateCategoryError"></div>
												</div>
											</div>
										</div>
										<div id="section6" class="hidden section-content">
											<h4 class="hidden-xs"><small>ELIMINAR UNA CATEGORÍA</small></h4>
											<hr>
											<h2><i class="fa fa-times fa-left"></i>Eliminar una categoría</h2>
											<div class="cajita">
												<div class="form-group">
													<label class="compulsory" for="deletedCategory">Selecciona la categoría</label>
													<select id="deletedCategory" name="deletedCategory" class="form-control">';

														while ($category4 = mysqli_fetch_array($categories4, MYSQL_NUM)) {
															$html_admin .= '<option value="' . $category4[0] . '">' . $category4[1] . '</option>';
														}

			$html_admin .= '
													</select>
												</div>
												<div class="checkbox">
													<label class="compulsory">
														<input type="checkbox" id="sureDeleteCategory">
														 Estoy seguro de que quiero eliminar la categoría y su contenido definitivamente
													</label>
												</div>
												<div class="form-group" style="margin-top: 20px;">												
													<button type="button" id="deleteCategory" class="btn btn-primary">Eliminar categoría</button>
													<div style="margin-top: 20px;" id="deleteCategoryError"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';

			//	Contenido en HTML
			$html_nosession = '<div class="container form-signin-container">
								<h2 class="form-signin-heading">
									Inicio de sesión
								</h2>
								<form class="form-signin" method="post">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
											<input type="email" name="inputEmail" id="inputEmail" class="form-control" placeholder="E-mail" required autofocus autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
											<input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Contraseña" required autofocus autocomplete="off">
										</div>
									</div>
									<button class="btn btn-lg btn-primary btn-block" type="submit">
										Entrar
									</button>
								</form>

							</div> <!-- /container -->';

			//	Muestra contenido o no
			if(!$_SESSION['user']) {
				//	Muestra formulario de ingreso
				echo $html_nosession;
			}
			else {
				$user = $_SESSION['user'];
				//	Comprobar permisos
				if($user['admin'] != 1) {
					//	Muestra mensaje de acceso denegado
					printError('Acceso denegado', ' style="max-width: 400px;"');
				}
				else {
					//	Muestra panel de control
					echo $html_admin;
				}
			}

			//	Comprobación de que se manda algo
			if ($_POST['inputEmail'] && $_POST['inputPassword']) {

				// Comprobación del envio del nombre de usuario y password
				$inputEmail = $_POST['inputEmail'];
				$inputPassword = $_POST['inputPassword'];

				//	Encuentra usuario
				$query = "SELECT * FROM users WHERE email = '$inputEmail'";
				$result = $connection -> query($query);
				$user = $result -> fetch_array(MYSQLI_ASSOC);

				//	Comprobación de contraseña
				if($user && $user['password'] == $inputPassword) {

					$_SESSION["user"] = $user;
					
					// Actualiza la fecha del último login
					date_default_timezone_set('Europe/Madrid');
					$fecha = date('Y/m/d H:i:s');
					mysqli_query($connection, "UPDATE users SET lastlogin = '$fecha' WHERE email = '$inputEmail'");

					//	Comprobar permisos
					if($user['admin'] != 1) {
						//	Muestra mensaje de acceso denegado
						printError('Acceso denegado', ' style="max-width: 400px;"');
					}
					else {
						//	Muestra panel de control
						header('Location: ' . '../admin');
					}
				}
				else {
					printError('Credenciales incorrectas', ' style="max-width: 400px;"');
				}
			}
			//	Cerrar conexión
			mysqli_close($connection);
		?>
	</body>
</html>