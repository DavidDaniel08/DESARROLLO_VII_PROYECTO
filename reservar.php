<?php
session_start();

// Incluir el archivo de conexión a la base de datos y PHPMailer manualmente
include 'conexion.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirigir al login si no está autenticado
    exit();
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['user_id']; // Asegúrate de que 'user_id' esté correctamente en la sesión

// Verificar si los datos del vuelo están presentes
if (isset($_POST['origen']) && isset($_POST['destino']) && isset($_POST['fecha']) && isset($_POST['hora_salida']) && isset($_POST['hora_llegada']) && isset($_POST['precio']) && isset($_POST['aerolinea']) && isset($_POST['escala']) && isset($_POST['duracion']) && isset($_POST['avion']) && isset($_POST['clases_disponibles']) && isset($_POST['disponibilidad']) && isset($_POST['descripcion']) && isset($_POST['imagen'])) {

    // Obtener los datos del vuelo desde el formulario
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $fecha = $_POST['fecha'];
    $hora_salida = $_POST['hora_salida'];
    $hora_llegada = $_POST['hora_llegada'];
    $precio = $_POST['precio'];
    $aerolinea = $_POST['aerolinea'];
    $escala = $_POST['escala'];
    $duracion = $_POST['duracion'];
    $avion = $_POST['avion'];
    $clases_disponibles = json_encode($_POST['clases_disponibles']); // Convertir las clases disponibles a formato JSON
    $disponibilidad = $_POST['disponibilidad'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];

    // Verifica que el usuario_id no sea nulo
    if ($usuario_id !== null) {
        try {
            // Inserción de la reserva en la base de datos
            $sql_reserva = "INSERT INTO reservas (usuario_id, origen, destino, fecha, hora_salida, hora_llegada, precio, aerolinea, escala, duracion, avion, clases_disponibles, disponibilidad, descripcion, imagen) 
                            VALUES (:usuario_id, :origen, :destino, :fecha, :hora_salida, :hora_llegada, :precio, :aerolinea, :escala, :duracion, :avion, :clases_disponibles, :disponibilidad, :descripcion, :imagen)";
            $stmt_reserva = $conn->prepare($sql_reserva);
            $stmt_reserva->bindParam(':usuario_id', $usuario_id);
            $stmt_reserva->bindParam(':origen', $origen);
            $stmt_reserva->bindParam(':destino', $destino);
            $stmt_reserva->bindParam(':fecha', $fecha);
            $stmt_reserva->bindParam(':hora_salida', $hora_salida);
            $stmt_reserva->bindParam(':hora_llegada', $hora_llegada);
            $stmt_reserva->bindParam(':precio', $precio);
            $stmt_reserva->bindParam(':aerolinea', $aerolinea);
            $stmt_reserva->bindParam(':escala', $escala);
            $stmt_reserva->bindParam(':duracion', $duracion);
            $stmt_reserva->bindParam(':avion', $avion);
            $stmt_reserva->bindParam(':clases_disponibles', $clases_disponibles);
            $stmt_reserva->bindParam(':disponibilidad', $disponibilidad);
            $stmt_reserva->bindParam(':descripcion', $descripcion);
            $stmt_reserva->bindParam(':imagen', $imagen);

            // Ejecutar la consulta de reserva
            $stmt_reserva->execute();

            // Obtener el correo electrónico del usuario
            $query_email = "SELECT email FROM usuarios WHERE id = :usuario_id";
            $stmt_email = $conn->prepare($query_email);
            $stmt_email->bindParam(':usuario_id', $usuario_id);
            $stmt_email->execute();
            $email_usuario = $stmt_email->fetchColumn();

            // Enviar correo de confirmación
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'izayasanchez0624@gmail.com'; 
                $mail->Password = 'axmjxlbglvjjkazg'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Destinatarios
                $mail->setFrom('izayasanchez0624@gmail.com', 'Confirmacion de Reserva');
                $mail->addAddress($email_usuario); // Dirección del usuario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Confirmacion de Reserva de Vuelo';
                $mail->Body = "
                    <h2>Estimado/a Usuario,</h2>
                    <p>Gracias por realizar su reserva. Aquí están los detalles:</p>
                    <table>
                        <tr><td><strong>Origen:</strong></td><td>$origen</td></tr>
                        <tr><td><strong>Destino:</strong></td><td>$destino</td></tr>
                        <tr><td><strong>Fecha:</strong></td><td>$fecha</td></tr>
                        <tr><td><strong>Hora de Salida:</strong></td><td>$hora_salida</td></tr>
                        <tr><td><strong>Hora de Llegada:</strong></td><td>$hora_llegada</td></tr>
                        <tr><td><strong>Precio:</strong></td><td>$precio</td></tr>
                        <tr><td><strong>Aerolínea:</strong></td><td>$aerolinea</td></tr>
                        <tr><td><strong>Escala:</strong></td><td>$escala</td></tr>
                        <tr><td><strong>Duración:</strong></td><td>$duracion</td></tr>
                        <tr><td><strong>Descripción:</strong></td><td>$descripcion</td></tr>
                    </table>
                    <p>¡Buen viaje!</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                echo "Error al enviar correo: {$mail->ErrorInfo}";
            }

            // Mostrar mensaje de éxito y redirigir a principal.php después de 3 segundos
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Reserva Exitosa</title>
                <link rel='stylesheet' href='estilo/exito_reserva.css'>
            </head>
            <body>
                <div class='reserva-exitosa'>
                    <h1>¡Reserva Exitosa!</h1>
                    <div class='gancho'></div>
                </div>

                <script>
                    // Redirigir a principal.php después de 3 segundos
                    setTimeout(function() {
                        window.location.href = 'principal.php';
                    }, 3000);
                </script>
            </body>
            </html>
            ";
        } catch (PDOException $e) {
            echo "Error al realizar la reserva: " . $e->getMessage();
        }
    } else {
        echo "Error: No se pudo obtener el ID del usuario.";
    }
} else {
    echo "Error: Datos del vuelo no válidos.";
}
?>
