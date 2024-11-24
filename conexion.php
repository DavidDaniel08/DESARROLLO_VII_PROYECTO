<?php
$host = '127.0.0.1'; 
$dbname = 'gestor_usuarios'; 
$user = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
