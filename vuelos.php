<?php
// Establecer la ruta absoluta del archivo JSON
$json_file = 'C:/laragon/www/DESARROLLO_VII_DAVID_ALVAREZ/PROYECTO/vuelos.json';

// Comprobar si el archivo existe
if (file_exists($json_file)) {
    // Leer el contenido del archivo JSON
    $vuelos_json = file_get_contents($json_file);

    // Convertir el contenido JSON en un array de PHP
    $vuelos = json_decode($vuelos_json, true);

    // Filtrar vuelos según los parámetros de búsqueda (si los hay)
    if (isset($_GET['origen'])) {
        $vuelos = array_filter($vuelos, function($vuelo) {
            return strpos(strtoupper($vuelo['origen']), strtoupper($_GET['origen'])) !== false;
        });
    }

    if (isset($_GET['destino'])) {
        $vuelos = array_filter($vuelos, function($vuelo) {
            return strpos(strtoupper($vuelo['destino']), strtoupper($_GET['destino'])) !== false;
        });
    }

    if (isset($_GET['fecha'])) {
        $vuelos = array_filter($vuelos, function($vuelo) {
            return $vuelo['fecha'] == $_GET['fecha'];
        });
    }

    // Establecer el tipo de contenido como JSON
    header('Content-Type: application/json');

    // Devolver los resultados como JSON
    echo json_encode(array_values($vuelos));
} else {
    // Si el archivo no existe, devolver un error
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Archivo no encontrado']);
}
?>
