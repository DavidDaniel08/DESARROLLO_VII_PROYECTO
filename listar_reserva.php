<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); 
    exit();
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['user_id']; 

// Consultar las reservas realizadas por el usuario actual
try {
    // Consulta SQL para obtener las reservas del usuario autenticado
    $sql = "SELECT * FROM reservas WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();

    // Obtener todas las reservas
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las reservas: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="estilo/lista.css"> 
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <ul class="navbar-menu">
            <li><a href="principal.php" class="menu-item">Inicio</a></li>
                <li><a href="listar_reserva.php" class="menu-item">Reservas</a></li>
                <li><a href="soporte.php" class="menu-item">Soporte</a></li>
                <li><a href="logout.php" class="menu-item">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <h1>Mis Reservas</h1>

        <?php if (count($reservas) > 0): ?>
            <table class="reservas">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                        <th>Hora de Salida</th>
                        <th>Hora de Llegada</th>
                        <th>Precio</th>
                        <th>Aerolínea</th>
                        <th>Escala</th>
                        <th>Duración</th>
                        <th>Avión</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['origen']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['destino']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['hora_salida']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['hora_llegada']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['precio']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['aerolinea']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['escala']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['duracion']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['avion']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['descripcion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes reservas realizadas.</p>
        <?php endif; ?>
    </div>
</body>
</html>
