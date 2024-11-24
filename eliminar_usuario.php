<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Verificar si se ha pasado el parámetro 'id' en la URL
if (isset($_GET['id'])) {
    // Obtener el ID del usuario
    $id = intval($_GET['id']); // Convertir a entero para evitar inyecciones SQL

    try {
        // Consulta SQL para eliminar al usuario por ID
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Ejecutar la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir de vuelta a la página de gestión de usuarios con un mensaje de éxito
        header("Location: gestionar_usuarios.php?mensaje=Usuario eliminado correctamente");
        exit();
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al eliminar el usuario: " . $e->getMessage();
        exit();
    }
} else {
    // Si no se pasa el ID, redirigir con un mensaje de error
    header("Location: gestionar_usuarios.php?mensaje=ID no proporcionado");
    exit();
}
