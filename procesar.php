<?php
// Ruta al archivo JSON
$jsonFile = 'data.json';

// Leer datos existentes
$data = [];
if (file_exists($jsonFile)) {
	$json = file_get_contents($jsonFile);
	$data = json_decode($json, true);
	if (!is_array($data)) {
		$data = [];
	}
}

// Obtener el siguiente ID
$nextId = 1;
if (!empty($data)) {
	$ids = array_column($data, 'id');
	$nextId = max($ids) + 1;
}

// Recibir datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
	$apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
	$correo = isset($_POST['correo']) ? $_POST['correo'] : '';

	// Crear nuevo registro
	$nuevoRegistro = [
		'id' => $nextId,
		'nombre' => $nombre,
		'apellido' => $apellido,
		'correo' => $correo
	];

	// Agregar al array
	$data[] = $nuevoRegistro;

	// Guardar en el archivo JSON
	file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

	// Mensaje de éxito
	echo "Registro guardado exitosamente.";
} else {
	echo "No se recibieron datos.";
}
?>
