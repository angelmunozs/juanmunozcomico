<?php
	// Configura los datos de tu cuenta
	include('../config.php');
	include('../messages.php');
	session_start();
	// Conectar a la base de datos
	$connection = mysqli_connect($dbhost, $dbusername, $dbuserpass, $dbname);
	if (mysqli_connect_errno()) {
		printError(mysqli_connect_error());
	}
	//	Set collation
	mysqli_set_charset($connection, "utf8");
	//	Actualiza datos en DB
	$query = 'SELECT * FROM categories';
	if($_GET['id']) {
		$query .= ' WHERE id = ' . $_GET['id'];
	}
	$sql = mysqli_query($connection, $query);
	$category = array();

	while($row = mysqli_fetch_array ($sql)) {
		$bus = array(
			'id' => $row['id'],
			'category' => $row['category'],
			'location' => $row['location'],
			'disabled' => $row['disabled']
		);
		array_push($category, $bus);
	}
	//	Return as JSON
	echo json_encode($category);
	//	Cerrar conexión
	mysqli_close($connection);
?>