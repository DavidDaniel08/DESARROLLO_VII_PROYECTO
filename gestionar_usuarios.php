<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Consultar todos los usuarios
try {
    // Verificar si hay un término de búsqueda
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Si hay un término de búsqueda, modificar la consulta
    if ($searchTerm) {
        $sql = "SELECT id, username, email, is_admin FROM usuarios WHERE username LIKE :searchTerm OR email LIKE :searchTerm";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
    } else {
        // Consulta SQL para obtener todos los usuarios
        $sql = "SELECT id, username, email, is_admin FROM usuarios";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();

    // Obtener todas las filas de usuarios
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los usuarios: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo/gestor_usuario.css">
    <title>Gestionar Usuarios</title>
    <script>
        // Función para confirmar la eliminación de un usuario
        function confirmarEliminacion(username) {
            return confirm(`¿Estás seguro de que deseas eliminar al usuario "${username}"? Esta acción no se puede deshacer.`);
        }

        // Función para confirmar si desea cambiar el estado de administrador
        function confirmarCambioEstado(username, accion) {
            const mensaje = accion === 'hacer_admin'
                ? `¿Estás seguro de convertir a "${username}" en administrador?`
                : `¿Estás seguro de revocar los permisos de administrador a "${username}"?`;
            return confirm(mensaje);
        }
    </script>
</head>
<body>
    <div class="search-bar">
        <form action="gestionar_usuarios.php" method="get" style="display: inline;">
            <input 
                type="text" 
                name="search" 
                placeholder="Buscar por nombre o correo" 
                value="<?php echo htmlspecialchars($searchTerm); ?>" 
                required>
            <button type="submit">Buscar</button>
        </form>
        
        <!-- Botón para listar todos los usuarios -->
        <form action="gestionar_usuarios.php" method="get" style="display: inline;">
            <input type="hidden" name="search" value="">
            <button type="submit">Listar Todos</button>
        </form>
    </div>

    <header>
        <nav class="navbar">
            <ul class="navbar-menu">
                <li><a href="admindashboard.php">Dashboard</a></li>
                <li><a href="">Paquetes</a></li>
                <li><a href="gestionar_usuarios.php">Usuarios</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['username'] ?? 'No disponible'); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email'] ?? 'No disponible'); ?></td>
                        <td><?php echo htmlspecialchars($usuario['is_admin'] ? 'Sí' : 'No'); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>&accion=<?php echo $usuario['is_admin'] ? 'revocar' : 'hacer_admin'; ?>"
                               onclick="return confirmarCambioEstado('<?php echo htmlspecialchars($usuario['username']); ?>', '<?php echo $usuario['is_admin'] ? 'revocar' : 'hacer_admin'; ?>')">
                               <?php echo $usuario['is_admin'] ? 'Revocar Admin' : 'Hacer Admin'; ?>
                            </a> |
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['id']; ?>"
                               onclick="return confirmarEliminacion('<?php echo htmlspecialchars($usuario['username']); ?>')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se encontraron resultados para "<?php echo htmlspecialchars($searchTerm); ?>"</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
