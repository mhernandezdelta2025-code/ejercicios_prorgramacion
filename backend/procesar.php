<?php
// Ruta al archivo JSON
$jsonFile = __DIR__ . '/../db/data.json';

// Asegurarse de que el directorio db existe
if (!file_exists(dirname($jsonFile))) {
    mkdir(dirname($jsonFile), 0777, true);
}

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

    // Validar datos
    if (empty($nombre) || empty($apellido) || empty($correo)) {
        http_response_code(400);
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Crear nuevo registro
    $nuevoRegistro = [
        'id' => $nextId,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'correo' => $correo,
        'fecha_registro' => date('Y-m-d H:i:s')
    ];

    // Agregar al array
    $data[] = $nuevoRegistro;

    // Guardar en el archivo JSON
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
        http_response_code(500);
        echo "Error al guardar los datos.";
        exit;
    }

    // Redirigir de vuelta al formulario con un mensaje de éxito
    header('Location: ../views/index.php?success=1');
    exit;
} else {
    // Si alguien intenta acceder directamente a este archivo sin enviar el formulario
    header('Location: ../views/index.php');
    exit;
}
