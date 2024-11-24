<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="estilo/adminStyle.css"> 
</head>
<body>
    <!-- Navbar -->
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

    <!-- Contenido Principal -->
    <div class="container">
        <h1>Panel de Administración</h1>
    </div>
</body>
</html>

