<?php
// Incluir PHPMailer manualmente
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    if (!empty($nombre) && !empty($email) && !empty($mensaje) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'izayasanchez0624@gmail.com'; 
            $mail->Password = 'axmjxlbglvjjkazg'; // Tu contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad
            $mail->Port = 587; // Puerto SMTP

            // Configurar el correo
            $mail->setFrom($email, $nombre); // Correo del remitente
            $mail->addAddress('izayasanchez0624@gmail.com'); // Correo al que se enviará el mensaje

            $mail->Subject = "Nueva consulta de soporte de $nombre";
            $mail->Body = "Nombre: $nombre\nCorreo: $email\n\nMensaje:\n$mensaje";

            $mail->send();
            echo "Gracias, $nombre. Tu mensaje ha sido enviado con éxito.";
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Por favor, completa todos los campos correctamente.";
    }
} else {
    //echo "Método de solicitud no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="estilo/soporte.css">
</head>
<body>
    <!-- Menú de navegación -->
    <header>
        <nav class="navbar">
            <ul class="navbar-menu">
                <li><a href="principal.php" class="menu-item">Inicio</a></li>
                <li><a href="listar_reserva.php" class="menu-item">Reservas</a></li>
                <li><a href="listar_reserva.php" class="menu-item">Soporte</a></li>
                <li><a href="logout.php" class="menu-item">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <h2>Contacto y Soporte</h2>
    <form action="soporte.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="asunto">Asunto:</label>
        <input type="text" id="asunto" name="asunto" required>
        <br><br>

        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
        <br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>