<?php
	// Configura los datos de tu cuenta
	include('../config.php');
	include('../functions.php');
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
	$query = 'SELECT * FROM logs ORDER BY id DESC';
	if($_GET['limit']) {
		$query .= ' LIMIT ' . $_GET['limit'] . ' OFFSET 0';
	}
	$sql = mysqli_query($connection, $query);
	$logs = array();

	while($row = mysqli_fetch_array ($sql)) {
		$bus = array(
			'id' => $row['id'],
			'idUser' => $row['idUser'],
			'ip' => $row['ip'],
			'createdAt' => $row['createdAt'],
			'description' => $row['description']
		);
		array_push($logs, $bus);
	}
	//	Return as JSON
	echo json_encode($logs);
	//	Cerrar conexión
	mysqli_close($connection);
?>