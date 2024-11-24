<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Verifica si el nombre de usuario y correo existen
    $sql = "SELECT * FROM usuarios WHERE username = :username AND email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username, 'email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Hash de la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $updateSql = "UPDATE usuarios SET password = :password WHERE username = :username AND email = :email";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute(['password' => $hashedPassword, 'username' => $username, 'email' => $email]);

        // Mensaje de éxito
        $successMessage = "Tu contraseña ha sido cambiada exitosamente.";
    } else {
        $errorMessage = "El nombre de usuario o el correo electrónico no son correctos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="estilo/recu_contra.css">
    <script>
        // Función para ocultar el mensaje después de 3 segundos
        function hideMessage() {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
                document.getElementById('success-message').style.display = 'none';
            }, 3000); // 3000 milisegundos = 3 segundos
        }

        // Redirigir después de mostrar el mensaje de éxito
        <?php if (isset($successMessage)): ?>
            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000); // Redirigir después de 3 segundos
        <?php endif; ?>
    </script>
</head>
<body>

<div class="container">
    <h2>Cambiar Contraseña</h2>

    <form action="recuperar_contraseña.php" method="POST">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" name="new_password" required>

        <button type="submit">Cambiar Contraseña</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <div id="error-message" class="message">
            <?php echo $errorMessage; ?>
        </div>

        <script>
            // Llamamos a la función para que el mensaje de error se oculte después de 3 segundos
            hideMessage();
        </script>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <div id="success-message" class="success">
            <?php echo $successMessage; ?>
        </div>

        <script>
            // Llamamos a la función para que el mensaje de éxito se oculte después de 3 segundos
            hideMessage();
        </script>
    <?php endif; ?>

</div>

</body>
</html>
