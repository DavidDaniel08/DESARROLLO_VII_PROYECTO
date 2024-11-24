<?php
include 'conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifra la contraseña

    // Inserta el usuario en la base de datos
    $sql = "INSERT INTO usuarios (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
        $successMessage = "Registro exitoso"; // Mensaje de éxito
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { // Error de duplicado (usuario o correo)
            $errorMessage = "El usuario o el correo electrónico ya existen."; 
        } else {
            $errorMessage = "Error en el registro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="estilo/registro_diseño.css"> 
    <script>
        // Función para ocultar el mensaje después de 3 segundos
        function hideMessage(messageId) {
            setTimeout(function() {
                document.getElementById(messageId).style.display = 'none';
            }, 3000);
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
    <h2>Crear Cuenta</h2>

    <form action="registro.php" method="POST">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Registrar</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <div id="error-message" class="message">
            <?php echo $errorMessage; ?>
        </div>
        <script>
            // Llamamos a la función para que el mensaje de error se oculte después de 3 segundos
            hideMessage('error-message');
        </script>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <div id="success-message" class="success">
            <?php echo $successMessage; ?>
        </div>
        <script>
            // Llamamos a la función para que el mensaje de éxito se oculte después de 3 segundos
            hideMessage('success-message');
        </script>
    <?php endif; ?>

</div>

</body>
</html>
