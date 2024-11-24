<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir al login si no está autenticado
    exit();
}

// Obtener el nombre de usuario desde la sesión
$usuario = $_SESSION['usuario'];

// Solicitar los datos de los vuelos desde la API
$vuelos_json = file_get_contents('http://localhost/DESARROLLO_VII_DAVID_ALVAREZ/PROYECTO/vuelos.php'); 
$vuelos = json_decode($vuelos_json, true);

// Filtrar vuelos solo si el formulario de búsqueda ha sido enviado
$origen = isset($_GET['origen']) ? $_GET['origen'] : '';
$destino = isset($_GET['destino']) ? $_GET['destino'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$listar = isset($_GET['listar']); 

// Solo filtrar si hay criterios de búsqueda
$filtrados = [];
if ($origen || $destino || $fecha) {
    $filtrados = array_filter($vuelos, function($vuelo) use ($origen, $destino, $fecha) {
        return (
            (!$origen || stripos($vuelo['origen'], $origen) !== false) &&
            (!$destino || stripos($vuelo['destino'], $destino) !== false) &&
            (!$fecha || stripos($vuelo['fecha'], $fecha) !== false)
        );
    });
} elseif ($listar) {
    $filtrados = $vuelos; 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Vuelos</title>
    <link rel="stylesheet" href="estilo/principal.css">
</head>
<body>

    <!-- Menú de navegación -->
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

    <!-- Contenedor principal -->
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1> 

        <!-- Formulario de búsqueda -->
        <div class="form-container">
            <form method="GET">
                <label for="origen">Origen:</label>
                <input type="text" id="origen" name="origen" value="<?php echo htmlspecialchars($origen); ?>">
                <label for="destino">Destino:</label>
                <input type="text" id="destino" name="destino" value="<?php echo htmlspecialchars($destino); ?>">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
                <button type="submit">Buscar</button>
                <button type="submit" name="listar" value="true">Listar Vuelos</button>
            </form>
        </div>

        <!-- Información sobre viajar (Solo visible si no hay búsqueda ni "Listar Vuelos") -->
        <?php if (!$origen && !$destino && !$fecha && !$listar): ?>
            <div class="info-container">
                <h2>¿Por qué es divertido viajar?</h2>
                <p>Viajar te permite conocer nuevas culturas, disfrutar de la gastronomía local, hacer nuevas amistades y descubrir paisajes increíbles. Ya sea en avión, tren o barco, cada viaje es una aventura única. Además, viajar te ayuda a salir de tu zona de confort y a ver el mundo desde una nueva perspectiva.</p>

                <h3>Lugares más famosos para viajar:</h3>
                <ul>
                    <li><strong>París, Francia:</strong> La ciudad del amor, famosa por la Torre Eiffel, el Louvre y su deliciosa gastronomía.</li>
                    <li><strong>Tokio, Japón:</strong> La mezcla perfecta de tradición y modernidad, conocida por su tecnología, templos y cultura.</li>
                    <li><strong>Roma, Italia:</strong> Una ciudad llena de historia, con el Coliseo, el Vaticano y muchas maravillas arquitectónicas.</li>
                    <li><strong>New York, Estados Unidos:</strong> La ciudad que nunca duerme, famosa por la Estatua de la Libertad, Times Square y Central Park.</li>
                    <li><strong>Sydney, Australia:</strong> Conocida por la Ópera de Sídney, hermosas playas y una vibrante vida nocturna.</li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Resultados de la búsqueda o lista de vuelos -->
        <?php if ($origen || $destino || $fecha || $listar): ?>
            <h2>Resultados de la búsqueda</h2>
            <ul class="vuelos-list">
                <?php if ($filtrados): ?>
                    <?php foreach ($filtrados as $vuelo): ?>
                        <li class="vuelo-item">
                            <img src="<?php echo $vuelo['imagen']; ?>" alt="Imagen del vuelo" class="vuelo-img">
                            <div class="vuelo-details">
                                <strong>Origen:</strong> <?php echo $vuelo['origen']; ?><br>
                                <strong>Destino:</strong> <?php echo $vuelo['destino']; ?><br>
                                <strong>Fecha:</strong> <?php echo $vuelo['fecha']; ?><br>
                                <strong>Aerolínea:</strong> <?php echo $vuelo['aerolinea']; ?><br>
                                <strong>Escala:</strong> <?php echo $vuelo['escala']; ?><br>
                                <strong>Duración:</strong> <?php echo $vuelo['duracion']; ?><br>
                                <strong>Precio:</strong> $<?php echo $vuelo['precio']; ?><br>
                                <strong>Disponibilidad:</strong> <?php echo $vuelo['disponibilidad']; ?> asientos<br>
                                <strong>Descripción:</strong> <?php echo $vuelo['descripcion']; ?><br>

                                <!-- Botón de reservar -->
                                <form action="reservar.php" method="POST">
                                    <input type="hidden" name="origen" value="<?php echo $vuelo['origen']; ?>">
                                    <input type="hidden" name="destino" value="<?php echo $vuelo['destino']; ?>">
                                    <input type="hidden" name="fecha" value="<?php echo $vuelo['fecha']; ?>">
                                    <input type="hidden" name="hora_salida" value="<?php echo $vuelo['hora_salida']; ?>">
                                    <input type="hidden" name="hora_llegada" value="<?php echo $vuelo['hora_llegada']; ?>">
                                    <input type="hidden" name="precio" value="<?php echo $vuelo['precio']; ?>">
                                    <input type="hidden" name="aerolinea" value="<?php echo $vuelo['aerolinea']; ?>">
                                    <input type="hidden" name="escala" value="<?php echo $vuelo['escala']; ?>">
                                    <input type="hidden" name="duracion" value="<?php echo $vuelo['duracion']; ?>">
                                    <input type="hidden" name="avion" value="<?php echo $vuelo['avion']; ?>">
                                    <input type="hidden" name="clases_disponibles" value="<?php echo htmlspecialchars(json_encode($vuelo['clases_disponibles'])); ?>">
                                    <input type="hidden" name="disponibilidad" value="<?php echo $vuelo['disponibilidad']; ?>">
                                    <input type="hidden" name="descripcion" value="<?php echo $vuelo['descripcion']; ?>">
                                    <input type="hidden" name="imagen" value="<?php echo $vuelo['imagen']; ?>">
                                    <button type="submit" class="reservar-btn">Reservar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">No se encontraron vuelos.</p>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

    </div>
</body>
</html>
