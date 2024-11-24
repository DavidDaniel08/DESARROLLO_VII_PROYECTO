<?php
include 'conexion.php';

// Verificar si el parámetro 'id' y 'accion' están definidos
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id = intval($_GET['id']);
    $accion = $_GET['accion'];

    try {
        // Determinar la acción a realizar
        $nuevoEstado = ($accion === 'hacer_admin') ? 1 : 0;

        // Actualizar el estado de administrador del usuario
        $sql = "UPDATE usuarios SET is_admin = :nuevoEstado WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir con un mensaje de éxito
        $mensaje = $nuevoEstado ? "Usuario convertido en administrador." : "Privilegios de administrador revocados.";
        header("Location: gestionar_usuarios.php?mensaje=" . urlencode($mensaje));
        exit();
    } catch (PDOException $e) {
        echo "Error al actualizar los privilegios del usuario: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: gestionar_usuarios.php?mensaje=Parámetros no válidos.");
    exit();
}
?>
